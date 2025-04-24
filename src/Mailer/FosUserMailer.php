<?php

namespace App\Mailer;

use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Mailer\MailerInterface as SymfonyMailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class FosUserMailer implements MailerInterface
{
    private $mailer;
    private $router;
    private $twig;
    private $parameters;

    public function __construct(
        SymfonyMailerInterface $mailer,
        UrlGeneratorInterface $router,
        Environment $twig,
        array $parameters = []
    ) {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
        $this->parameters = $parameters;
    }

    public function sendConfirmationEmailMessage(UserInterface $user): void
    {
        $template = $this->parameters['template']['confirmation'];
        $url = $this->router->generate('fos_user_registration_confirm', [
            'token' => $user->getConfirmationToken(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $context = [
            'user' => $user,
            'confirmationUrl' => $url,
        ];

        $this->sendMessage($template, $context, $this->parameters['from_email']['address'], (string) $user->getEmail());
    }

    public function sendResettingEmailMessage(UserInterface $user): void
    {
        $template = $this->parameters['template']['resetting'];
        $url = $this->router->generate('fos_user_resetting_reset', [
            'token' => $user->getConfirmationToken(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $context = [
            'user' => $user,
            'confirmationUrl' => $url,
        ];

        $this->sendMessage($template, $context, $this->parameters['from_email']['address'], (string) $user->getEmail());
    }

    protected function sendMessage(string $templateName, array $context, string $fromEmail, string $toEmail): void
    {
        $template = $this->twig->load($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        $email = (new Email())
            ->from($fromEmail)
            ->to($toEmail)
            ->subject($subject)
            ->text($textBody)
            ->html($htmlBody);

        $this->mailer->send($email);
    }
} 
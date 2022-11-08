<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TestController extends AbstractController
{

    #[Route('/test')]
    public function test(HttpClientInterface $client): Response
    {
        $response = $client->request(
            'POST',
            'https://api.openai.com/v1/completions',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $_ENV['OPENAPI_API_KEY'],
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => "text-davinci-002",
                    'prompt' => 'Ce commentaires est-il conforme ? Oui ou non ? Commentaire : "Je déteste le service client de cette entreprise"',
                    'max_tokens' => 10,
                    'temperature' => 0.5,
                    'top_p' => 1,
                    'frequency_penalty' => 0,
                    'presence_penalty' => 0,
                ],
            ]
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->toArray();

        return new Response($content['choices'][0]['text']);
    }

    //send test email
    #[Route('/test/email')]
    public function testEmail(MailerInterface $mailer): Response
    {
        $email = (new TemplatedEmail())
            ->from(new Address('no-response@forhum.fr', 'FoRhum'))
            ->to('test@forhum.fr')
            ->subject('Test email')
            ->htmlTemplate('email/confirmation_email.html.twig')
            ->context([
                'name' => 'test',
                'signedUrl' => 'test',
                'expiresAtMessageKey' => 'test',
                'expiresAtMessageData' => 'test',
            ]);
        $mailer->send($email);
        return new Response('Email sent');

    }

}
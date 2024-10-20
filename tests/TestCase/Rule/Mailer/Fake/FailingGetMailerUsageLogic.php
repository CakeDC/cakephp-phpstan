<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Test\TestCase\Rule\Mailer\Fake;

use Cake\Mailer\MailerAwareTrait;

class FailingGetMailerUsageLogic
{
    use MailerAwareTrait;

    /**
     * @return void
     */
    public function execute(): void
    {
        $this->getMailer('MyTestLoad')->testing();//This is okay
        $this->getMailer('OldArticles')->testing();//This is NOT okay
        $this->getMailer('SomeArticle')->send('published', ['userId' => 10, 'articleId' => 999]);
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ImportFailedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private string $batch,
        private string $errorMessage,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Import Failed')
            ->error()
            ->greeting("Hello {$notifiable->name}!")
            ->line("Your import (batch: {$this->batch}) failed.")
            ->line("Error: {$this->errorMessage}")
            ->action('Try Again', route('admin.import.index'))
            ->line('Please check your file format and try again.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'    => 'import_failed',
            'batch'   => $this->batch,
            'message' => "Import failed: {$this->errorMessage}",
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}

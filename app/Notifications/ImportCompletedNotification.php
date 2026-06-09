<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ImportCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private string $batch,
        private int $rowCount,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Import Completed Successfully')
            ->greeting("Hello {$notifiable->name}!")
            ->line("Your import (batch: {$this->batch}) completed successfully.")
            ->line("{$this->rowCount} records were imported.")
            ->action('View Records', route('admin.import.records'))
            ->line('Thank you for using our system.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'      => 'import_completed',
            'batch'     => $this->batch,
            'row_count' => $this->rowCount,
            'message'   => "{$this->rowCount} records imported successfully.",
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}

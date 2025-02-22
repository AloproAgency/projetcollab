<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Project;

class ProjectNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $project;
    public $messageContent;

    /**
     * Create a new messageContent instance.
     *
     * @param Project $project
     * @param string $messageContent
     */
    public function __construct(Project $project, string $messageContent)
    {
        $this->project = $project;
        $this->messageContent = $messageContent;
    }

    /**
     * Build the messageContent.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Notification de Projet')
                    ->view('emails.project_notification') // Vue Blade
                    ->with([
                        'project' => $this->project,
                        'messageContent' => $this->messageContent,
                    ]);
    }
}
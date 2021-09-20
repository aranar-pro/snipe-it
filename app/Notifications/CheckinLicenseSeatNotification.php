<?php

namespace App\Notifications;

use App\Models\LicenseSeat;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsChannel;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class CheckinLicenseSeatNotification extends Notification
{
    use Queueable;
    /**
     * @var
     */
    private $params;

    /**
     * Create a new notification instance.
     *
     * @param $params
     */
    public function __construct(LicenseSeat $licenseSeat, $checkedOutTo, User $checkedInBy, $note)
    {
        $this->target = $checkedOutTo;
        $this->item = $licenseSeat->license;
        $this->admin = $checkedInBy;
        $this->note = $note;
        $this->settings = Setting::getSettings();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via()
    {
        $notifyBy = [];

        if (Setting::getSettings()->slack_endpoint != '') {
            $notifyBy[] = 'slack';
        }

        if (Setting::getSettings()->msteams_endpoint != '') {
            \Log::debug('use msteams');
            $notifyBy[2] = MicrosoftTeamsChannel::class;
        }

        /**
         * Only send checkin notifications to users if the category
         * has the corresponding checkbox checked.
         */
        if ($this->item->checkin_email() && $this->target instanceof User && $this->target->email != '') {
            $notifyBy[] = 'mail';
        }

        return $notifyBy;
    }

    public function toSlack()
    {
        $target = $this->target;
        $admin = $this->admin;
        $item = $this->item;
        $note = $this->note;
        $botname = ($this->settings->slack_botname) ? $this->settings->slack_botname : 'Snipe-Bot';

        if ($admin) {
            $fields = [
                'To' => '<'.$target->present()->viewUrl().'|'.$target->present()->fullName().'>',
                'By' => '<'.$admin->present()->viewUrl().'|'.$admin->present()->fullName().'>',
            ];
        } else {
            $fields = [
                'To' => '<'.$target->present()->viewUrl().'|'.$target->present()->fullName().'>',
                'By' => 'CLI tool',
            ];
        }

        return (new SlackMessage)
            ->content(':arrow_down: :floppy_disk: '.trans('mail.License_Checkin_Notification'))
            ->from($botname)
            ->attachment(function ($attachment) use ($item, $note, $admin, $fields) {
                $attachment->title(htmlspecialchars_decode($item->present()->name), $item->present()->viewUrl())
                    ->fields($fields)
                    ->content($note);
            });
    }





    public function toMicrosoftTeams($notifiable)
    {
        $expectedCheckin = 'None';
        $target = $this->target;
        $admin = $this->admin;
        $item = $this->item;
        $note = $this->note ?: 'No note provided.';

        return MicrosoftTeamsMessage::create()
            ->to(Setting::getSettings()->msteams_endpoint)
            ->type('success')
            ->addStartGroupToSection($sectionId = 'action_msteams')
            ->title('&#x2B07;&#x1F4BE; License Checked In: <a href=' . $item->present()->viewUrl() . '>' . $item->present()->fullName() . '</a>', $params = ['section' => 'action_msteams'])
            ->content($note, $params = ['section' => 'action_msteams'])
            ->fact('By', '<a href=' . $admin->present()->viewUrl() . '>' . $admin->present()->fullName() . '</a>', $sectionId = 'action_msteams')
            ->button('View in Browser', '' . $target->present()->viewUrl() . '', $params = ['section' => 'action_msteams']);
    }




    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail()
    {
        return (new MailMessage)->markdown('notifications.markdown.checkin-license',
            [
                'item'          => $this->item,
                'admin'         => $this->admin,
                'note'          => $this->note,
                'target'        => $this->target,
            ])
            ->subject(trans('mail.License_Checkin_Notification'));
    }
}

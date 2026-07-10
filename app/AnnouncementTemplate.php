<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnnouncementTemplate extends Model
{
    protected $guarded = [];

    /** Seed the default bilingual (FR/EN) templates if they do not exist yet. */
    public static function seedDefaults()
    {
        foreach (self::defaults() as $order => $tpl) {
            self::firstOrCreate(
                ['key' => $tpl['key']],
                [
                    'name' => $tpl['name'],
                    'subject' => $tpl['subject'],
                    'header' => $tpl['header'],
                    'body' => $tpl['body'],
                    'footer' => $tpl['footer'],
                    'sort_order' => $order,
                    'is_active' => true,
                ]
            );
        }
    }

    public static function defaults(): array
    {
        $header = '<p>Mulema Gospel Talent</p>';
        $footer = '<p>Mulema Gospel Talent<br>www.mulemagc.com</p>';

        return [
            [
                'key' => 'voting_start_date',
                'name' => 'Voting will start on (date)',
                'subject' => 'Voting Start Date / Date de début des votes',
                'header' => $header,
                'body' => '<p><strong>FR:</strong> Bonjour {name}, les votes ouvriront le <strong>{date}</strong>. Préparez-vous à soutenir votre candidat préféré.</p>'
                    . '<p><strong>EN:</strong> Hello {name}, voting will start on <strong>{date}</strong>. Get ready to support your favourite contestant.</p>',
                'footer' => $footer,
            ],
            [
                'key' => 'voting_started',
                'name' => 'Voting has started',
                'subject' => 'Voting Is Open / Les votes sont ouverts',
                'header' => $header,
                'body' => '<p><strong>FR:</strong> Bonjour {name}, les votes sont maintenant <strong>ouverts</strong> ! Rendez-vous sur www.mulemagc.com pour voter.</p>'
                    . '<p><strong>EN:</strong> Hello {name}, voting is now <strong>open</strong>! Visit www.mulemagc.com to cast your vote.</p>',
                'footer' => $footer,
            ],
            [
                'key' => 'voting_end_date',
                'name' => 'Voting will end on (date)',
                'subject' => 'Voting End Date / Date de clôture des votes',
                'header' => $header,
                'body' => '<p><strong>FR:</strong> Bonjour {name}, les votes se clôtureront le <strong>{date}</strong>. Votez avant la fermeture !</p>'
                    . '<p><strong>EN:</strong> Hello {name}, voting will end on <strong>{date}</strong>. Vote before it closes!</p>',
                'footer' => $footer,
            ],
            [
                'key' => 'voting_ended',
                'name' => 'Voting has ended',
                'subject' => 'Voting Closed / Les votes sont clôturés',
                'header' => $header,
                'body' => '<p><strong>FR:</strong> Bonjour {name}, les votes sont désormais <strong>clôturés</strong>. Merci pour votre participation.</p>'
                    . '<p><strong>EN:</strong> Hello {name}, voting has now <strong>ended</strong>. Thank you for taking part.</p>',
                'footer' => $footer,
            ],
            [
                'key' => 'grading_ready',
                'name' => 'Grading is ready',
                'subject' => 'Grading Ready / Notation disponible',
                'header' => $header,
                'body' => '<p><strong>FR:</strong> Bonjour {name}, la <strong>notation</strong> est prête. Les résultats sont désormais disponibles.</p>'
                    . '<p><strong>EN:</strong> Hello {name}, <strong>grading</strong> is ready. Results are now available.</p>',
                'footer' => $footer,
            ],
        ];
    }
}

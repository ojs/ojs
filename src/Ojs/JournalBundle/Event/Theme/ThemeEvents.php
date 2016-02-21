<?php

namespace Ojs\JournalBundle\Event\Theme;

use Ojs\CoreBundle\Events\EventDetail;
use Ojs\CoreBundle\Events\MailEventsInterface;

final class ThemeEvents implements MailEventsInterface
{
    const LISTED = 'ojs.theme.list';

    const PRE_CREATE = 'ojs.theme.pre_create';

    const POST_CREATE = 'ojs.theme.post_create';

    const PRE_UPDATE = 'ojs.theme.pre_update';

    const POST_UPDATE = 'ojs.theme.post_update';

    const PRE_DELETE = 'ojs.theme.pre_delete';

    const POST_DELETE = 'ojs.theme.post_delete';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::POST_CREATE, 'journal', []),
            new EventDetail(self::POST_UPDATE, 'journal', []),
            new EventDetail(self::POST_DELETE, 'journal', []),
        ];
    }
}

<?php

namespace Controller\Admin {

    use Api;
    use Lib;

    class Edit extends \Controller\Me {

        public static function generate(array $params) {

            $bracket = self::_getBracket(array_shift($params));

            if ($bracket) {

                // Create the bracket on POST
                if ($_POST) {

                    $id = Lib\Url::Post('bracketId', true);
                    $name = Lib\Url::Post('bracketName');
                    $rules = Lib\Url::Post('rules');

                    if ($name && $rules) {
                        $bracket->name = trim($name);
                        $bracket->rules = $rules;
                        $bracket->nameLabel = Lib\Url::Post('nameLabel');

                        $sourceOn = Lib\Url::Post('hideSource') !== 'on';
                        $bracket->sourceLabel = $sourceOn ? Lib\Url::Post('sourceLabel') : 'NO_SOURCE';

                        $advanceHour = Lib\Url::Post('advanceHour', true);
                        $advanceHour = null !== $advanceHour ? $advanceHour : -1;
                        $bracket->advanceHour = $advanceHour;

                        if ($bracket->sync()) {

                            // Clear the generic bracket related caches
                            self::_refreshCaches($bracket);
                            header('Location: /me/?edited');
                            exit;
                        }

                    }

                }

                $bracket->sourceHidden = $bracket->sourceLabel === 'NO_SOURCE';
                $bracket->times = self::_generateAdvanceTimes($bracket->advanceHour);
                Lib\Display::renderAndAddKey('content', 'admin/bracket', $bracket);

            }

        }

    }

}
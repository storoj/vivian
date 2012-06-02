<?php
function getCategory($id) {
    $gl_categories = array( 1 => 'Графика', 2 => 'Архитектура', 3 => 'Брендинг', 4 => 'Веб-дизайн',
        5 => 'Гейм-дизайн', 6 => 'Графический дизайн', 7 => 'Граффити и стрит-арт', 8 => 'Дизайн интерфейсов',
        9 => 'Дизайн интерьера', 10 => 'Дизайн одежды', 11 => 'Дизайн персонажей', 12 => 'Дизайн упаковки',
        13 => 'Иллюстрация', 14 => 'Концепт-арт', 15 => 'Логотипы', 16 => 'Мода', 17 => 'Моушн графика',
        18 => 'Полиграфический дизайн', 19 => 'Промышленный дизайн', 20 => 'Реклама', 21 => 'Ретушь',
        22 => 'Рисунок', 23 => 'Скульптура', 24 => 'Технический дизайн', 25 => 'Типографика', 26 => 'Фотография',
        27 => 'Хенд-мейд', 28 => 'Цифровое искусство', 29 => 'Эскизы');

    return (array_key_exists($id, $gl_categories)) ? $gl_categories[$id] : 0;
}

function getLicense($id) {
    $gl_license = array(1 => 'Все права защищены',2 => 'С указанием авторства',
    3 => 'С указанием авторства — Без производных', 4 => 'С указанием авторства — Некоммерческая — Без производных',
    5 => 'С указанием авторства — Некоммерческая', 6 => 'С указанием авторства — Некоммерческая — Копилефт',
    7 => 'Распространение на тех же условиях — Копилефт');

    return $gl_license[$id];
}

function checkEmail($email) {
    return preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', trim($email));
}

// TODO move this to User
function checkFriend($id) {
    $user = User::getInstance();

    $query = new DBQuery();
    $num = $query->table('friends')
        ->where(array('user_id' => $id, 'subscriber_id' => $user->id))
        ->count();

    return $num;
}

function getResizedFileName($path_mod, $modifier) {
    // todo :: remake
    $path = explode("/", $path_mod);
    $path[count($path) - 1] = preg_replace("/([\d]*)[^.]*\.([\w]{3,4})$/", "$1_".$modifier.".$2", $path[count($path) - 1]);

    $new_path = implode("/", $path);

    return $new_path;
}

function checkAvatar($path, $size = 42) {
    if ($path) {
        ($size == 42) ? $to_find = 'main' : $to_find = 'tmb'.$size;
        $src = getResizedFileName($path, $to_find);
        if (!file_exists(PATH_ROOT . $src)) {
            $src = '/img/avatars/default_'.$size.'.png';
        }
    } else {
        $src = '/img/avatars/default_'.$size.'.png';
    }

    return $src;
}

function getMultipleForm($n, $form_0, $form_1, $form_2) {
    if ($n >= 11 && $n <= 19)
        return $n." ".$form_0;
    elseif ($n % 10 == 1)
        return $n." ".$form_1;
    elseif (($n % 10 == 2) || ($n % 10 == 3) || ($n % 10 == 4))
        return $n." ".$form_2;
    else
        return $n." ".$form_0;
}

function formatDate($date_val) {
    // TODO set this function
    $diff = time() - $date_val;
    if ($diff < 60) {
        return 'только что';
    } elseif ($diff < 3600) {
        $res = floor($diff / 60);
        if ($res == 1) {
            return 'минуту назад';
        } else {
            return getMultipleForm($res, 'минут', 'минуту', 'минуты'). ' назад';
        }
    } elseif ($diff < 86400) {
        $res = floor($diff / 3600);
        if ($res == 1) {
            return 'час назад';
        } else {
            return getMultipleForm($res, 'часов', 'час', 'часа'). ' назад';
        }
    } elseif ($diff < 2592000) {
        $res = floor($diff / 86400);
        if ($res == 1) {
            return 'вчера';
        } else {
            return getMultipleForm($res, 'дней', 'день', 'дня'). ' назад';
        }
    } else {
        $months = array('января','февраля','марта','апреля','мая','июня','июля',
            'августа','сентярбря','октября','ноября','декабря');
        $str = date('j', $date_val) . ' '
            . $months[intval(date('n', $date_val)) - 1];
        if (date('Y') != date('Y', $date_val)) {
            $str .= ' ' . date('Y', $date_val);
        }

        return $str;
    }
}

function formatFileSize($size) {
    if ($size < 1024) {
        return $size.' b';
    } elseif ($size < 1048576) {
        return ceil($size/1024).' Kb';
    } elseif ($size < 1073741824) {
        return ceil($size/1048576).' Mb';
    } else {
        return ceil($size/1073741824).' Gb';
    }
}

?>
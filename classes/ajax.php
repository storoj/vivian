<?php
/*
 * This class is used for simple actions
 * to escape loading big classes
 */

class Ajax extends AbstractPage {

    public function action_find_user($arParams = array()) {
        $nick = $this->data['receiver'];
        if ($nick) {
            $query = new DBQuery();
            $user = $query->table('users')
                ->getFields(array('id', 'avatar_img'))
                ->where(array('nickname' => $nick))
                ->limit(1)
                ->fetch();

            if (is_array($user)){
                $this->setStatus('ok', 'Пользователь найден');
                $this->add_to_wrap['id'] = $user['id'];
                $this->add_to_wrap['avatar_img'] = checkAvatar($user['avatar_img']);
            } else {
                $this->setStatus('error', 'Пользователь НЕ найден');
            }

        } else {
            $this->setStatus('error', 'Пользователь НЕ найден - пусто');
        }

        return $this->wrapAjax();
    }

    public function action_check_nickname() {
        $nick = $this->data['nickname'];
        $len = strlen($nick);

        if ($len < 3) {
            $this->setStatus('error', 'Логин слишком короткий');
            return $this->wrapAjax();
        } elseif($len > 20) {
            $this->setStatus('error', 'Логин слишком длинный');
            return $this->wrapAjax();
        }

        if (!preg_match('#^[\w\d_]+$#',$nick)) {
            $this->setStatus('error', 'Недопустимый формат');
            return $this->wrapAjax();
        }

        $query = new DBQuery();
        $res = $query->table('users')
            ->where(array('nickname' => $nick))
            ->count();

        if (is_numeric($res) && $res > 0) {
            $this->setStatus('error', 'Такой логин уже занят');
        } else {
            $this->setStatus('ok');
        }

        return $this->wrapAjax();
    }
}

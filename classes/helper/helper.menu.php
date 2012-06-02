<?php

class MenuHelper extends TemplatifyHelper{
    private $designer_list = array('profile' => 'Профиль','projects' => 'Мои проекты', 'bookmarks' => 'Закладки',
        'relations' => 'Отношения', 'comments' => 'Комментарии', 'messages/inbox' => 'Сообщения',
        'friends' => 'Друзья', 'groups' => 'Группы', 'references' => 'Отзывы', 'account' => 'Мой счет',
        'settings' => 'Настройки');
    private $designer_list_pro = array('statistics' => 'Статистика');

    private $employer_list = array('profile' => 'Профиль', 'orders' => 'Мои заявки',
        'favorites' => 'Избранное', 'messages/inbox' => 'Сообщения', 'references' => 'Отзывы',
        'account' => 'Мой счет', 'settings' => 'Настройки');
    private $employer_list_pro = array();

    private $side_menu_list = array(
        'profile'       => array('projects/add' => '+ Добавить проект'),
        'projects'      => array('' => 'Все проекты', 'add' => 'Добавить проект',
                'comments' => 'Комментарии на мои проекты'),
        'comments'      => array('' => 'Мои комментарии', 'projects' => 'Комментарии к моим проектам'),
        'messages'      => array('inbox' => 'Входящие', 'sent' => 'Отправленные', 'new' => 'Написать сообщение'),
        'groups'        => array('add' => 'Создать группу'),
        'account'       => array('' => 'Пополнить баланс', 'payment' => 'Оплата услуг',
                'present' => 'Сделать подарок', 'history' => 'История платежей', 'transfer' => 'Перевести деньги'),
        'references'    => array('' => 'Отзывы', 'answers' => 'Мои ответы'),
        'settings'      => array('' => 'Обзор настроек', 'account' => 'Аккаунт', 'about' => 'Обо мне',
                'privacy' => 'Приватность', 'notification' => 'Оповещения')
    );

    private $user_types = array('1' => 'designer', '2' => 'employer');

    public  $source;
    public  $main_menu;
    public  $side_menu;
    public  $settings_block;

    public  $action = array();
    public $user;

    function __construct() {
        // TODO exclude global param
        global $url;

        // for main user menu
        $this->action['main'] = $url[0];
        //print_r($this->action);

        $this->user = User::getInstance();

        if ($this->user->is_logged) {
            $this->generateMainMenu();

            // side user menu existance check
            if (array_key_exists($this->action['main'], $this->side_menu_list)) {
                // action for side menu
                $this->action['side'] = isset($url[1]) ? $url[1] : '';
                $this->generateSideMenu();
            }
        }
    }

    private function generateMainMenu() {
        $source_name = $this->user_types[$this->user->user_type]."_list";
        $source_name_pro = $this->user_types[$this->user->user_type]."_list_pro";

        $this->source = $this->$source_name;
        if ($this->user->pro && !empty($this->$source_name_pro) && is_array($this->source)) {
            $this->source = array_merge($this->source, $this->$source_name_pro);
        }

        $this->main_menu = $this->templatify('mainmenu');
    }

    private function generateSideMenu() {
        if (array_key_exists($this->action['main'], $this->side_menu_list)
            && !empty($this->side_menu_list[$this->action['main']])) {
            $this->source = $this->side_menu_list[$this->action['main']];

            $this->side_menu = $this->templatify('side_menu');
        }
    }

    public function generateSideOptional($structure, $alias = NULL) {
        $this->source = $structure;

        return $this->templatify('side_menu_optional', array($alias));
    }
}

<h2>Статичные страницы</h2>

<?if (is_array($this->list) && count($this->list) > 0) {?>
    <table class="table table-bordered table-striped table-condensed">
        <thead>
            <tr>
                <th>ID</th>
                <th>URL</th>
                <th>Заголовок</th>
                <th>Содержимое</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?foreach($this->list as $el) {?>
                <tr>
                    <td width="30"><?=$el['id'];?></td>
                    <td><?=$el['url'];?></td>
                    <td><?=$el['title'];?></td>
                    <td><?=$el['text'];?></td>
                    <td width="32">
                        <a title="Редактировать" href="#"><i class="icon-edit"></i></a>
                        <a title="Удалить" href="#"><i class="icon-remove"></i></a>
                    </td>
                </tr>
            <?}?>
        </tbody>
    </table>
<?}?>

<button type="button" class="btn btn-primary">Добавить страницу</button>
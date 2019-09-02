    <nav class="nav">
      <ul class="nav__list container">
        <?php foreach ($category_list as $key => $val) { ?>
          <li class="nav__item">
            <a href="pages/all-lots.html"><?=htmlspecialchars($val['name_cat']); ?></a>
          </li>
        <?php }; ?>
      </ul>
    </nav>
    <form class="form form--add-lot container <?=count($error_list) ? 'form--invalid' : ''; ?>" action="add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
      <h2>Добавление лота</h2>
      <div class="form__container-two">
        <div class="form__item <?=isset($error_list['lot-name']) ? 'form__item--invalid' : ''; ?>"> <!-- form__item--invalid -->
          <label for="lot-name">Наименование <sup>*</sup></label>
          <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?=getPostVal('lot-name'); ?>">
          <span class="form__error"><?=$error_list['lot-name'] ?? ""; ?></span>
        </div>
        <div class="form__item <?=isset($error_list['category']) ? 'form__item--invalid' : ''; ?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category">
                <option value="">Выберите категорию</option>
                <?php foreach ($category_list as $key => $val) { ?>
                    <option value="<?=$val['id_cat']; ?>"><?=htmlspecialchars($val['name_cat']); ?></option>
                <?php }; ?>
            </select>
            <span class="form__error"><?=$error_list['category'] ?? ""; ?></span>
        </div>
      </div>
      <div class="form__item form__item--wide <?=isset($error_list['message']) ? 'form__item--invalid' : ''; ?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите описание лота"><?=getPostVal('message'); ?></textarea>
        <span class="form__error"><?=$error_list['message'] ?? ""; ?></span>
      </div>
      <div class="form__item form__item--file <?=isset($error_list['lot-img']) ? 'form__item--invalid' : ''; ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
          <input class="visually-hidden" type="file" name='lot-img' id="lot-img" value="">
          <span class="form__error"><?=$error_list['lot-img'] ?? ""; ?></span>
          <label for="lot-img">
            Добавить
          </label>
        </div>
      </div>
      <div class="form__container-three">
        <div class="form__item form__item--small <?=isset($error_list['lot-rate']) ? 'form__item--invalid' : ''; ?>">
          <label for="lot-rate">Начальная цена <sup>*</sup></label>
          <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?=getPostVal('lot-rate'); ?>">
          <span class="form__error"><?=$error_list['lot-rate'] ?? ""; ?></span>
        </div>
        <div class="form__item form__item--small <?=isset($error_list['lot-step']) ? 'form__item--invalid' : ''; ?>">
          <label for="lot-step">Шаг ставки <sup>*</sup></label>
          <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?=getPostVal('lot-step'); ?>">
          <span class="form__error"><?=$error_list['lot-step'] ?? ""; ?></span>
        </div>
        <div class="form__item <?=isset($error_list['lot-date']) ? 'form__item--invalid' : ''; ?>">
          <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
          <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?=getPostVal('lot-date'); ?>">
          <span class="form__error"><?=$error_list['lot-date'] ?? ""; ?></span>
        </div>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <button type="submit" class="button">Добавить лот</button>
    </form>

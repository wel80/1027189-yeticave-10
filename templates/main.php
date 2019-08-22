    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <!--заполните этот список из массива категорий-->
            <?php foreach ($category_list as $key => $val) { ?>
                <li class="promo__item promo__item--<?=htmlspecialchars($val['code_cat']); ?>">
                    <a class="promo__link" href="pages/all-lots.html"><?=htmlspecialchars($val['name_cat']); ?></a>
                </li>
            <?php }; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <!--заполните этот список из массива с товарами-->
            <?php foreach ($advertising_list as $key => $val) { ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?=htmlspecialchars($val["image"]); ?>" width="350" height="260" alt="<?=htmlspecialchars($val["name"]); ?>">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?=htmlspecialchars($val["category"]); ?></span>
                        <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?=htmlspecialchars($val["name"]); ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?php if ($val["price_rate"]) {print(price_format(htmlspecialchars($val["price_rate"])));} else {print(price_format(htmlspecialchars($val["price"])));} ?></span>
                            </div>
                            <div class="lot__timer timer <?php if (rest_time($val["date_expiry"])[0] == '00') {print('timer—finishing');} ?>">
                                <?php print((rest_time($val["date_expiry"])[0] . ' : ' . rest_time($val["date_expiry"])[1])); ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php }; ?>
        </ul>
    </section>
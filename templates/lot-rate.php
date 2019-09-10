          <div class="lot-item__state">
            <div class="lot-item__timer timer <?=(rest_time($is_lot["date_expiry"])[0] == '00') ?'timer—finishing':''; ?>">
              <?php print((rest_time($is_lot["date_expiry"])[0] . ' : ' . rest_time($is_lot["date_expiry"])[1])); ?>
            </div>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?=price_format(htmlspecialchars($is_lot['current_price'])) ; ?></span>
              </div>
              <div class="lot-item__min-cost">
                Мин. ставка <span><?=price_format(htmlspecialchars($is_lot['min_rate'])); ?></span>
              </div>
            </div>
            <form class="lot-item__form" action="lot.php?id=<?=$id; ?>" method="post" autocomplete="off">
              <p class="lot-item__form-item form__item <?=$error ? 'form__item--invalid' : ''; ?>">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="text" name="cost" placeholder="Сделайте ставку" value="<?=getPostVal('cost'); ?>">
                <span class="form__error"><?=$error ?? ""; ?></span>
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
          </div>
          
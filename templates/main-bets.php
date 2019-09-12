    <nav class="nav">
      <ul class="nav__list container">
        <?php foreach ($category_list as $val) { ?>
          <li class="nav__item">
            <a href="pages/all-lots.html"><?=$val['name_cat']; ?></a>
          </li>
        <?php }; ?>
      </ul>
    </nav>
    <section class="rates container">
      <h2>Мои ставки</h2>
      <table class="rates__list">
        <?php foreach ($my_bet_list as $val) { ?>
        <tr class="rates__item <?=rates_item($val['completion_period'],  $val['winner_id']); ?>">
          <td class="rates__info">
            <div class="rates__img">
              <img src="<?=$val['image_lot']; ?>" width="54" height="40" alt="<?=$val['name_lot']; ?>">
            </div>
            <div>
            <h3 class="rates__title"><a href="lot.php?id=<?=$val['id_lot']; ?>"><?=$val['name_lot']; ?></a></h3>
            <p><?=rates_contact($val['completion_period'],  $val['winner_id'], $val['contact']); ?></p>
            </div>
          </td>
          <td class="rates__category">
          <?=$val['name_cat']; ?>
          </td>
          <td class="rates__timer">
            <div class="timer <?=rates_timer_class($val['completion_period'],  $val['winner_id']); ?>">
              <?=rates_timer_content($val['completion_period'],  $val['winner_id'], $val['completion_date']); ?>
            </div>
          </td>
          <td class="rates__price">
          <?=price_format_rate($val['bet_amount']); ?>
          </td>
          <td class="rates__time">
          <?=passedTime($val['period_day'], $val['period_min'], $val['day_month_year'], $val['hour_min']); ?>
          </td>
        </tr>
        <?php }; ?>
      </table>
    </section>

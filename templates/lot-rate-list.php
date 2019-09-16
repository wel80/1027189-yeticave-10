          <div class="history">
            <h3>История ставок (<span><?=$count_rate; ?></span>)</h3>
            <table class="history__list">
            <?php foreach ($rate_list as $val) { ?>
              <tr class="history__item">
                <td class="history__name"><?=$val["name_user"]; ?></td>
                <td class="history__price"><?=price_format_rate($val['bet_amount']); ?></td>
                <td class="history__time"><?=passedTime($val['period_day'], $val['period_min'], $val['day_month_year'], $val['hour_min']); ?></td>
              </tr>
            <?php }; ?>
            </table>
          </div>

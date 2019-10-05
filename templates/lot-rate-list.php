          <div class="history">
            <h3>История ставок (<span><?=isset($count_rate) ? $count_rate : ''; ?></span>)</h3>
            <table class="history__list">
            <?php foreach ($rate_list as $val) { ?>
              <tr class="history__item">
                <td class="history__name"><?=isset($val["name_user"]) ? htmlspecialchars($val["name_user"]) : ''; ?></td>
                <td class="history__price"><?=isset($val['bet_amount']) ? price_format_rate($val['bet_amount']) : ''; ?></td>
                <td class="history__time"><?=isset($val['period_day']) && isset($val['period_min']) && isset($val['day_month_year']) && isset($val['hour_min']) ? 
                passedTime($val['period_day'], $val['period_min'], $val['day_month_year'], $val['hour_min']) : ''; ?></td>
              </tr>
            <?php } ?>
            </table>
          </div>

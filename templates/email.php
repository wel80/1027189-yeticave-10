<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?=isset($lot_winner['name_user']) ? htmlspecialchars($lot_winner['name_user']) : ''; ?></p>
<p>Ваша ставка для лота <a href="<?=isset($lot_url) ? $lot_url : ''; ?>"><?=isset($lot_winner['name_lot']) ? htmlspecialchars($lot_winner['name_lot']) : ''; ?></a> победила.</p>
<p>Перейдите по ссылке <a href="<?=isset($rate_list_url) ? $rate_list_url : ''; ?>">мои ставки</a>, чтобы связаться с автором объявления</p>
<small>Интернет Аукцион "YetiCave"</small>
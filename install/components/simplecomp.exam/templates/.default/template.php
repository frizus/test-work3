<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<div class="container mt-4">
    <h1 class="h4 mb-4">Каталог</h1>
    <?if (empty($arResult['GROUPS'])):?>
        <p class="lead">Товаров нет.</p>
    <?else:?>
        <div class="table-responsive">
            <table class="table table-sm table-striped table-hover">
                <?foreach ($arResult['GROUPS'] as $group):?>
                    <thead>
                    <tr class="table-primary">
                        <td colspan="4">
                            <?=$group['NAME']?>
                            <?
                            if (isset($group['ACTIVE_FROM'])) {
                                echo ' &ndash; ' . $group['ACTIVE_FROM']->format('d.m.Y');
                            }
                            ?>
                            <?
                            $first = true;
                            echo '<span class="small text-muted">(';
                            foreach ($group['CATALOG_SECTIONS'] as $catalogSection) {
                                if (!$first) {
                                    echo ', ';
                                } else {
                                    $first = false;
                                }

                                echo $catalogSection['NAME'];
                            }
                            echo ')</small>';
                            ?>
                        </td>
                    </tr>
                    <tr class="table-primary">
                        <th>Название</th>
                        <th>Цена</th>
                        <th>Материал</th>
                        <th>Артикул</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?foreach ($group['PRODUCTS'] as $product):?>
                        <tr>
                            <td><?=$product['NAME']?></td>
                            <td><?=$product['PROPERTIES']['PRICE']['VALUE']?></td>
                            <td><?=$product['PROPERTIES']['MATERIAL']['VALUE']?></td>
                            <th><?=$product['PROPERTIES']['CML2_ARTICLE']['VALUE']?></th>
                        </tr>
                    <?endforeach?>
                    </tbody>
                <?endforeach?>
            </table>
        </div>
    <?endif?>
</div>

<div class="col-md-12">
    <h3 class="card-title">{$title}</h3>

    <table class="table">
        <tr>
            <th>Вид занятия</th>
            <th>Стоимость, руб.</th>
        </tr>

    {foreach $service as $row}

            <tr>
                <td>
                    {$row.title}
                </td>
                <td>
                    {$row.price_min}
                </td>
            </tr>
    {/foreach}


    </table>
</div>
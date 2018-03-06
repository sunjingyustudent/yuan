
<table class="table table-hover" style="width:100%;">
    <tr style="font-size: 24px;">
        <td>
            用户名
        </td>
        <td>
            房间名
        </td>
        <td>
            礼物名称
        </td>
        <td>
            礼物价格
        </td>
    </tr>
    <?php foreach ($gift as $arr): ?>
        <tr >
            <td  >
                <?= $arr['username'] ?>
            </td>
            <td >
                <?= $arr['roomtitle'] ?>
            </td>
            <td >
                <?= $arr['giftname'] ?> 
            </td>
            <td >
                <?= $arr['giftprice'] ?>元宝
            </td>

        </tr>
    <?php endforeach; ?>
</table>



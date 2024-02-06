<?php
if($list['rows']){ 
?>
<ul class="list-task">
<?php foreach($list['rows'] as $r){ ?>
    <li>
        <a href="<?=site_url($r['url'])?>" class="waves-effect waves-block">
            <div class="icon-circle bg-<?=$r['bg']?>">
                <i class="material-icons"><?=$r['icon']?></i>
            </div>
            <div class="menu-info">
                <p class="info"><?=$r['info']?></p>
                <p>
                    <i class="material-icons">access_time</i> <?=$r['time']?> 
                    <i class="material-icons">account_circle</i> <?=$r['user']?>
                </p>
            </div>
        </a>
    </li>
<?php } ?>
</ul>
  <?=UI::showPaging($paging,$page, $limit_arr,$limit,$list)?>
<?php } else{ ?>
<i>Tidak ada task</i>
<?php
}
?>
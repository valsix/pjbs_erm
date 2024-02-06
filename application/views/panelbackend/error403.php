<div class="container-fluid five-zero-zero">
        <div class="five-zero-zero-container">
        <div class="error-code">403</div>
        <div class="error-message">
        Access denied
        <?php if($error_str){ ?>
        <p style="text-align: center;font-size: 12px"><?=$error_str?></p>
        <?php } ?>
        </div>
        <div class="button-place">
            <a href="<?=base_url("panelbackend/home")?>" class="btn btn-default btn-lg waves-effect">DASHBOARD</a>
        </div>
    </div>
</div><!--/#error-->
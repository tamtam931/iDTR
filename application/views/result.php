<div class="main-body container">
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
            <h2>Employee's Time Record and Leave Balances</h2>
            <p>Date Range: <strong class="date_start" data-attr="<?= $this->input->post("date_start") ?>"><?= date("F d, Y",strtotime($this->input->post("date_start"))) ?></strong> to <strong class="date_end" data-attr="<?= $this->input->post("date_end") ?>"><?= date("F d, Y",strtotime($this->input->post("date_end"))) ?></strong></p>
            <p class="access_no text-hide"><?= $this->input->post("access_no") ?></p>        
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <a href="<?php echo base_url();?>" class="float-right"><button class="btn btn-primary btn-md">Home</button></a>
        </div>        
    </div>
    <div class="card dtr-panel border-primary">
        <div class="card-heading">
            <ul class="list-inline-block" style="margin: 10px 0 0 0;">
                <?php
                    if ($balances) {
                        foreach ($balances as $data) {
                            ?>
                                <li class="list-inline-item">
                                    <?php 
                                        if ($data['LeaveCode'] == 'VL') {
                                           echo "VL Balance =";

                                        } else if ($data['LeaveCode'] == 'SL') {
                                            echo "SL Balance =";

                                        } else {

                                            echo "EL Balance =";
                                        }
                                    ?>
                                    <strong><?php echo ($data['Balances'] ? number_format($data['Balances'],3) : '0'); ?></strong>
                                </li>
                            <?php
                        }                        
                    }
                ?>
            </ul>
        </div>
        <div class="card-body">
            <table class="table table-borderd-responsive table-hover">
                <thead>
                    <tr class="table-info">
                        <th>Access #</th>
                        <th>Date/Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="data-res">

                </tbody>
            </table>
            <div class="progress"></div>                        
        </div>
    </div>
    <hr>
    <a href="<?php echo base_url();?>"><button class="btn btn-primary btn-md">Home</button></a>
    <div class="pagination_link pagination float-right"></div>	
</div>
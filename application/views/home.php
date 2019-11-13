<div class="main-body container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 offset-md-2 col-md-8 offset-lg-3 col-lg-5">
            <div class="card border-primary">
                <div class="card-header text-center" style="padding: 0;">
                    <img src="<?php echo base_url();?>public/img/FLI_logo.png" alt="FLI_logo.png" class="img-fluid">
                </div>
                <div class="card-body">
                    <form action="<?php echo base_url('Show'); ?>">
                        <div class="form-group">
                            <label for="access_no">Access #</label>
                            <input type="text" class="form-control" id="access_no" name="access_no" placeholder="" value="">
                        </div>
                            <hr>
                        <div class="form-group">
                            <label for="date_start">From</label>
                            <input type="date" class="form-control" id="date_start" name="date_start" placeholder="" value="">
                        </div>
                        <div class="form-group">
                            <label for="date_end">To</label>
                            <input type="date" class="form-control" id="date_end" name="date_end" placeholder="" value="">
                        </div>
                        <input type="submit" class="inq_tr btn btn-md btn-primary btn-block" value="Inquire">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
            <table class="table table-borderd-responsive table-hover">
                <thead>
                    <tr class="table-info">
                        <th>Access #</th>
                        <th>Date/Time</th>
                        <th>Status</th>
                        <th>Source Device</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if(!$result){
                        ?>
                            <tr class="table-danger">
                                <td><h4>No</h4></td>
                                <td><h4>Data</h4></td>
                                <td><h4>Found</h4></td>
                                <td></td>
                            </tr>
                        <?php
                        } else {

                            foreach ($result as $data) {
                            ?>
                                <tr>
                                    <td><?= $data['BiometricID'];?></td>
                                    <td><p class="font-weight-bold" style="margin-bottom: 0"><?= date("F d, Y",strtotime($data['DateTime'])) ?></p><p style="margin-top: 0"><?= date("h:i-a",strtotime($data['DateTime'])) ?></p></td>
                                    <td><strong><?= $data['Status'] ?></strong></td>
                                    <td><?= $data['SourceDevice'] ?></td>
                                </tr>
                            <?php
                            }                                    
                        }
                                
                    ?>
                </tbody>
            </table>
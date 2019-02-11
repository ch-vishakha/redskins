<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="row">
    <div class="col-sm-12">
        <div class="col-sm-6 pull-right">
            <div class="logo-image">
                <img src = <?= $this->Url->build('/')."webroot/img/logo.png"?> width="140px"> 
            </div>
            <div class="player-image">
                <img src=<?= $this->Url->build('/')."webroot/img/player-images.png" ?> style="position: fixed;">
            </div>
        </div>
        <div class="col-sm-6 pull-left" style="margin-top: 20px;">
            <h4 class="text-center">Bon Secours Training Center, 2401 W Lelgh St, Richmond, VA 23220</h4>
                <h4 class="text-center">
                    <strong>2018 Redskins Training Camp</strong>
                </h4>
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="text-center" style="color: white">Thanks for the registration</h3>
                        <?php 
                            foreach ($users as $key => $user): 
                        ?> 
                            <div class="text-center" style="color: white">
                                <h5><strong>GUESTS</strong></h5>
                                <?= h(strtoupper($user->first_name.' '.$user->last_name)); ?><br>
                                <span>
                                    <?= h($user->email); ?>
                                </span>
                            </div>
                            <br>
                        <?php   
                            endforeach; 
                        ?>
                        </div>
                    </div>
                 <?= $this->Form->create($user) ?>
                        <div class="invatation_details" style="color: #fff; padding-left: 31px;  letter-spacing: 1px; font-size: 13px;">
                            <p>Invite your friends and family to the Redskins Training Camp. You are not required to register kids 13 and under - your Fan Mobile Pass will also be valid for their entry.</p>
                        </div>
                    </fieldset>
                    <div class="guest_form" id = "friendForm">
                    </div>
                    <br><br>
                    <div class="input-field clearfix text-center">
                        <div class="col-md-12">
                            <input type="button" name="addfriends" value="+ Add Friends" class="formButton" id="add-invitee">
                            <?= $this->Form->button(__('Submit'), ['class' => ['formButton'], 'id' => 'saveUser']) ?>
                        </div>
                    </div>
                    <div class="basic_info">
                        <p>Entry is first come, first serve. Date of camp are subject to change. See complete schedule and more information at <a href="http://www.redskins.com/trainingcamp" target="_blank">redskins.com/trainingcamp.</a> <br><br>
                        * Please share my email address with NBC Universal, so NBC Universal can send me information about special offer and promotion. I have read and agree to <a href="https://tracking.cirrusinsight.com/6305dab5-367d-4a0f-a674-87d2e81e6e99/nbcuniversal-com-privacy" target="_blank"> NBC UNERVERSAL’S PRIVACY POLICY. </a><br><br><br></p>
                    </div>
                <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<!-- script for add more guest tab by clicking on button + Add Friends -->
<script type="text/javascript">
    $(document).ready(function() {
    var wrapper         = $("#friendForm"); //Fields wrapper
    var add_button      = $("#add-invitee"); //Add button ID
    
    var x = 0; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        var primaryUserEmail = $('#primaryUserEmail').val();
        e.preventDefault();
            $(wrapper).append('<div class="col-sm-12 col-sm-offset-1"><div class="col-sm-3"><div class="form-horizontal"><input class = "form-control inputForm" type="text" name="friends['+x+'][first_name]" placeholder="First Name"></div></div><div class="col-sm-3"><div class="form-horizontal"><input class = "form-control inputForm" type="text" name="friends['+x+'][last_name]" placeholder="Last Name"></div></div><div class="col-sm-3"><div class="form-horizontal"><input class = "form-control inputForm" type="email" name="friends['+x+'][email]" placeholder="Email"></div></div><div><input type="text" class = "added_by" hidden = true id="addedBy'+x+'" name="friends['+x+'][added_by]"></div><div class="col-sm-3 remove_field"><p style="color: #fff">X</p></div></div>'); //add input box
        
            $('input[id="addedBy'+x+'"]').val(primaryUserEmail);
            x++; //text box increment

    });
    
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault();
        if (confirm("Are you sure you want to remove this!")) {
            $(this).parent('div').remove(); x--;
        } 
    })
});
</script>
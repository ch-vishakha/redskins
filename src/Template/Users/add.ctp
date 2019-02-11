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
                <img src="webroot/img/logo.png" width="140px"> 
            </div>
            <div class="player-image">
                <img src="webroot/img/player-images.png" style="position: fixed;">
            </div>
        </div>
        <div class="col-sm-6 pull-left" style="margin-top: 20px;">
            <h4 class="text-center">Bon Secours Training Center, 2401 W Lelgh St, Richmond, VA 23220</h4>
                <h4 class="text-center">
                    <strong>2018 Redskins Training Camp</strong>
                </h4>
                <?php 
                    if(isset($userRegistered)){
                ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <h3>Thanks for the registration</h3>
                             <?php 
                            foreach ($userData as $key => $user): 
                        ?> 
                            <?= h(strtoupper($user->first_name.' '.$user->last_name)); ?>
                            <br>
                        <?php   
                            endforeach; 
                        ?>
                        </div>
                    </div>
                 <?= $this->Form->create($user) ?>
                <?php }else{ ?>
                <?= $this->Form->create($user) ?>
                    <fieldset>
                        <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <div class="form-horizontal">
                                    <input class = "form-control inputForm" type="text" name="first_name" placeholder="First Name">
                               </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-horizontal">
                                    <input class = "form-control inputForm" type="text" name="last_name" placeholder="Last Name">
                                </div>
                            </div>
                        </div>
                        <br><br><br>
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <div class="form-horizontal">
                                    <input class = "form-control inputForm" type="email" name="email" placeholder="Email" id="primaryUserEmail">
                               </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-horizontal">
                                    <input class = "form-control inputForm" type="number" name="zip_code" placeholder="Zip Code">
                                </div>
                            </div>
                        </div>
                        <br><br><br>
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <div class="form-horizontal">
                                    <?php
                                      echo  $this->Form->select('no_of_guests', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20], ['empty' => 'No. of Guests', 'class' => 'form-control',]);
                                    ?>
                               </div>
                            </div>
                        </div>
                        <div class="col-sm-12 show-activity">
                        <?php 
                            foreach ($activities as $key => $activity): 
                        ?>
                            <?= $this->Form->checkbox('user_activities[]', ['class' => 'checkbox', 'value' => $key ,'multiple' => true, 'hiddenField' => false]); ?>
                            <?= $activity ?>
                            <br><br>
                        <?php   
                            endforeach; 
                        ?>  
                        </div>
                        <div class="invatation_details" style="color: #fff; padding-left: 31px;  letter-spacing: 1px; font-size: 13px;">
                            <p>Invite your friends and family to the Redskins Training Camp. You are not required to register kids 13 and under - your Fan Mobile Pass will also be valid for their entry.</p>
                        </div>
                    </fieldset>
                    <?php } ?>
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

<!-- <style type="text/css">
    h4{
        font-size: 21px;
        color: white;
    }
    h4:nth-child(2){
        font-size: 21px;
        color: white;
        letter-spacing: 5px;
    }
    .logo-image>img{
        position: fixed;
        top: 45px;
    }
    .inputForm{
        background: 0 0;
        border: 0;
        color: #fff;
        border-bottom: 1px solid #fff;
        border-radius: 0;
        padding: 0;
        font-size: 15px; 
    }

    .inputForm:focus{
        box-shadow: none;
        border-bottom: 1px solid #fff;
        color: #fff;
    }

    select.form-control {
        background: 0 0!important;
        border: none;
        padding: 0;
        border-bottom: 1px solid #fff;
        border-radius: 0;
        margin-left: -3px;
        font-size: 17px;
    }

    select.form-control:focus{
        box-shadow: none;
        border-bottom: 1px solid #fff;
    }

    .show-activity{
        color: #fff;
        font-weight: 400;
        letter-spacing: 1px;
        font-size: 16px;
        margin-left: 29px;
        display: inline-block;
        margin-top: 20px;
        padding-right: 56px; 
    }

    .checkbox {
        min-height: 20px;
        padding-left: 20px;
        margin-bottom: 0;
        font-weight: 400;
        cursor: pointer;
        display: inline !important;
    }

     .formButton {
        background: #550119;
        border: 0;
        border-radius: 4px;
        color: #fff;
        outline: 0;
        height: 35px;
        letter-spacing: 0.2em;
        font-weight: bold;
        padding: 0px 25px 0px 25px;
        font-size: 17px;
        margin-right: 10px;
        text-transform: uppercase;
        /* box-shadow: 0px 5px 16px #483f3f; */
    }

    .basic_info {
        color: #fff;
        padding: 0 15px;
        font-size: 13px;
        font-style: italic;
        text-align: center;
    }

    input::-webkit-input-placeholder {
        color: white !important;
    }
     
    input:-moz-placeholder { /* Firefox 18- */
        color: white !important;  
    }
     
    input::-moz-placeholder {  /* Firefox 19+ */
        color: white !important;  
    }
     
    input:-ms-input-placeholder {  
     color: white !important;  
    }

</style> -->

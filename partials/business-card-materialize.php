<?php
/**
 * Materialize styled business card
 */
?>
<?php
/**
 * Preprocessing
 */
    global $jtzwpHelpers;
    
    $age = false;
    $birthday = $jtzwpHelpers->getThemeUserSetting('jtzwp_about_me_birthdate');
    $name = $jtzwpHelpers->getThemeUserSetting('jtzwp_about_me_displayed_name');
    $nameSubHeading = $jtzwpHelpers->getThemeUserSetting('jtzwp_about_me_name_subheading');
    $emailAddress = $jtzwpHelpers->getThemeUserSetting('jtzwp_about_me_email');
    $geography = $jtzwpHelpers->getThemeUserSetting('jtzwp_about_me_geo_description');
    $linkedInURL = $jtzwpHelpers->getThemeUserSetting('jtzwp_about_me_linkedin_url');
    $codingProfileURL = $jtzwpHelpers->getThemeUserSetting('jtzwp_about_me_coding_profile_url');
    $profilePictureFilePath = $jtzwpHelpers->getThemeUserSetting('jtzwp_about_me_profile_picture_filepath');


    // Validate minimum required fields are filled
    $minimumsMet = true;
    $requiredFields = array($name);
    foreach($requiredFields as $field){
        if ($field->isValid === false){
            $minimumsMet = false;
            break;
        }
    }
    // Calculate age based on birthday
    if ($birthday->isValid){
        $birthdayDateTime = new DateTime($birthday->val);
        $now = new DateTime('now');
        $ageDiff = date_diff($birthdayDateTime,$now,true);
        $age = $jtzwpHelpers->getDateDiffByUnit($ageDiff,'years');
    }
    // Check if profile picture exists
    $profilePictureURL = false;
    if ($profilePictureFilePath->val!=='' && file_exists($profilePictureFilePath->val)){
        $profilePictureURL = $profilePictureFilePath->val;
    }
    else if(get_site_icon_url(512)!==''){
        $profilePictureURL = get_site_icon_url(512);
    }
?>
<?php if($minimumsMet): ?>
<div class="businessCard materialize">
    <div class="background">
        <div class="diagonals"></div>
    </div>
    <div class="card-panel z-depth-4 hoverable">
        <div class="row valign-wrapper">
            <div class="col s8 leftSide">
                <div class="row nameAndTitleSection">
                    <div class="fullName col s12">Mr. Joshua Tzucker</div>
                    <div class="divider col s12" style="height: 2px;"></div>
                    <div class="jobTitle col s11 offset-s1">Professional Tinkerer</div>
                </div>
                <div class="row">
                    <div class="col s11 offset-s1 linksAndDetailsSection">
                        <?php if ($emailAddress->isValid): ?>
                            <div class="emailAddress col s12 valign-wrapper">
                                <div class="iconWrapper z-depth-2 iconsSolidBackground">
                                    <div class="icon">
                                        <i class="material-icons left">email</i>
                                    </div>
                                </div>
                                <div class="textWrapper">
                                    <a href="mailto:<?php echo $emailAddress->val; ?>?subject=I%20Found%20Your%20Website" target="_blank"><?php echo $emailAddress->val; ?></a>
                                </div>
                            </div>
                            <div class="rowJoinerWrapper"><div class="rowJoiner iconsSolidBackground"></div></div>
                        <?php endif; ?>
                        <?php if($geography->isValid): ?>
                            <div class="geography col s12 valign-wrapper">
                                <div class="iconWrapper z-depth-2 iconsSolidBackground">
                                    <div class="icon">
                                        <i class="material-icons left">map</i>
                                    </div>
                                </div>
                                <div class="textWrapper">
                                    <span><?php echo $geography->val; ?></span>
                                </div>
                            </div>
                            <div class="rowJoinerWrapper"><div class="rowJoiner iconsSolidBackground"></div></div>
                        <?php endif; ?>
                        <?php if ($linkedInURL->isValid): ?>
                            <div class="linkedInURL col s12 valign-wrapper">
                                <div class="iconWrapper z-depth-2 iconsSolidBackground">
                                    <div class="icon">
                                        <i class="material-icons left">link</i><i class="fa fa-linkedin-square iconsSolidBackground" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="textWrapper">
                                    <a href="<?php echo $linkedInURL->val; ?>" target="_blank">LinkedIn</a>
                                </div>
                            </div>
                            <div class="rowJoinerWrapper"><div class="rowJoiner iconsSolidBackground"></div></div>
                        <?php endif; ?>
                        <?php if($codingProfileURL->isValid): ?>
                            <div class="codingProfileURL col s12 valign-wrapper">
                                <div class="iconWrapper z-depth-2 iconsSolidBackground">
                                    <div class="icon">
                                        <i class="material-icons left">code</i><i class="fa fa-github iconsSolidBackground" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="textWrapper">
                                    <a href="https://github.com/joshuatz" target="_blank">Github</a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col s4 rightSide">
                <div class="valign-wrapper">
                    <?php if($profilePictureURL): ?>
                    <div class="profilePictureWrapper col s12 z-depth-2">
                        <img class="profilePicture responsive-img" src="<?php echo $profilePictureURL; ?>">
                    </div>
                    <?php endif; ?>
                </div>
                <?php if ($age): ?>
                    <div class="ageWrapper col s12 center">
                        <span>Age <?php echo $age; ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="businessCard materialize">
    <div class="background jtzwp-error">
    </div>
    <div class="card-panel z-depth-4 hoverable">
        <div class="row valign-wrapper jtzwp-error">
            joshuatz-wp settings is missing the minimum configured settings to display this card.
        </div>
    </div>
</div>
<?php endif; ?>
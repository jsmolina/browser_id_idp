<script src="<?=$bvp_browserid_js?>authentication_api.js"></script>
<script lang="Javascript">  
    <?php global $user; ?> 
    var phoneNumber = '<?=$user->name?>'; // global $user; $user->msisdn
    function fail() {
        var msg = 'user is not authenticated as target user'; /** @todo translate */
        navigator.id.raiseAuthenticationFailure(msg);
        /** @todo V2: show the login page */
    };
    
    /**
     * Browser callback function
     * email special case: not with user interaction coming as null on Gecko phone
     */
    function startSignin() {
        navigator.id.beginAuthentication(function(email) {
            var domPhoneNumber = phoneNumber + '@' + '<?=$bvp_browserid_domain?>';
            if(email === null || domPhoneNumber === email) {
                navigator.id.completeAuthentication();
            } else {
                fail();
            }

        });
    }
</script>
<h2><?=t('Welcome to signin');?></h2>
<button class="bluevia" onClick="startSignin();"><span>Signin</span></button>
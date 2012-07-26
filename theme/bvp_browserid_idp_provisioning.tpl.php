<html>
    <head>
        <script src="<?=$bvp_browserid_js?>provisioning_api.js"></script>
         <script type="text/javascript" src="/misc/jquery.js?U"></script>
        <script>
            function fail() {
                var msg = 'user is not authenticated as target user';
                navigator.id.raiseProvisioningFailure(msg);
            };
            /**
             * cert_duration suggestion on how_long
             * email special case: not with user interaction coming as null on Gecko phone
             */
            navigator.id.beginProvisioning(function(email, cert_duration) {
                <?php global $user; ?>
                var loggedUser = <?=$bvp_browserid_loggedin?>; 
                var domPhoneNumber = '<?=$bvp_browserid_email?>';
                 if(loggedUser && (email === null || domPhoneNumber === email)) {
                    navigator.id.genKeyPair(function(publicKey) {
                        var data = {
                            'email': domPhoneNumber,
                            'duration': cert_duration,
                            'publicKey': JSON.stringify(publicKey),
                            'xsrf': '<?=$bvp_browserid_xsrf?>'
                        };
                        
                        $.post('/browserid/gencert', data, function(certificate) {
                            navigator.id.registerCertificate(certificate); 
                            /** @todo csrf */
                        });
                        /** @todo add here special case for error response code*/
                    });
                 } else {
                     fail();
                 }
                
            });

        </script>
    </head>
    <body>
        
    </body>
</html>
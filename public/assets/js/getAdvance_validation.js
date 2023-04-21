if($("#advance_form").length > 0)
   {
    $('#advance_form').validate({ // initialize the plugin
        rules: {
            legal: {
                required: true,
            },
            is_decisionmaker: {
                required: true,
            },
            is_musicprimary: {
                required: true
            },
            is_entertainmentprimary: {
                required: true,
            },
            amount_period: {
                required: true,
            },
            period: {
                required: true,
             digits: true,
            },
        },
            messages:{
                legal: {
                    required: 'Please Select One Option'
                },
                is_decisionmaker: {
                    required: 'Please Select sole decision maker'
                },
                is_musicprimary: {
                    required: 'Please Select music industry'
                },
                is_entertainmentprimary: {
                    required: 'Please Select One Option'
                },
                amount_period: {
                    required: 'Please Select One Option for royalties'
                },
                period: {
                    required: 'Please Enter Years'
                }
            }
        });
   }




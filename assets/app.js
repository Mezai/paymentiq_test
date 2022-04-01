/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/global.scss';


// start the Stimulus application
import './bootstrap';


import _PaymentIQCashier from 'paymentiq-cashier-bootstrapper'

new _PaymentIQCashier('#cashier',
    {
        merchantId: '199001',
        userId: '12',
        environment: 'test',
        locale: 'sv_SE',
        mode: 'gambling',
        sessionId: '12367123123',
        amount: '30',
        method: 'deposit'
    },
    (api) => {
        console.log('Cashier intialized and ready to take down the empire');

        //if(window.screen.width < 320) {
            api.css(`
            .cashier-section-header {
                width: 100%;
            }
        `)
       // }



        // register callbacks
        api.on({
            cashierInitLoad: () => console.log('Cashier init load'),
            update: data => console.log('The passed in data was set', data),
            success: data => console.log('Transaction was completed successfully', data),
            failure: data => console.log('Transaction failed', data),
            pending: data => console.log('Transaction is pending', data),
            unresolved: data => console.log('Transaction is unresolved', data),
            isLoading: data => console.log('Data is loading', data),
            doneLoading: data => console.log('Data has been successfully downloaded', data),
            newProviderWindow: data => console.log('A new window / iframe has opened', data),
            paymentMethodSelect: data => console.log('Payment method was selected', data),
            paymentMethodPageEntered: data => console.log('New payment method page was opened', data),
            navigate: data => console.log('Path navigation triggered', data),
            cancelledPendingWD: data => console.log('A pending withdrawal has been cancelled', data),
            validationFailed: data => console.log('Transaction attempt failed at validation', data),
            cancelled: data => console.log('Transaction has been cancelled by user', data),
            onLoadError: data => console.log('Cashier could not load properly', data),
            transactionInit: data => console.log('A new transaction has been initiated', data)
        })
    }
)
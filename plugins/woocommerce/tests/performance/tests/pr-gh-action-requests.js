import { homePage } from '../requests/shopper/home.js';
import { shopPage } from '../requests/shopper/shop-page.js';
import { searchProduct } from '../requests/shopper/search-product.js';
import { singleProduct } from '../requests/shopper/single-product.js';
import { cart } from '../requests/shopper/cart.js';
import { cartRemoveItem } from '../requests/shopper/cart-remove-item.js';
import { checkoutGuest } from '../requests/shopper/checkout-guest.js';
import { checkoutCustomerLogin } from '../requests/shopper/checkout-customer-login.js';
import { myAccount } from '../requests/shopper/my-account.js';
import { categoryPage } from '../requests/shopper/category-page.js';
import { wpLogin } from '../requests/merchant/wp-login.js';
import { products } from '../requests/merchant/products.js';
import { addProduct } from '../requests/merchant/add-product.js';
import { orders } from '../requests/merchant/orders.js';
import { ordersSearch } from '../requests/merchant/orders-search.js';
import { homeWCAdmin } from '../requests/merchant/home-wc-admin.js';

export let options = {
    scenarios: {
        homePageSmoke: {
            executor: 'per-vu-iterations',
            vus: 1,
            iterations: 1,
            maxDuration: '10s',
            exec: 'homePageFlow',
        },
        shopPageSmoke: {
            executor: 'per-vu-iterations',
            vus: 1,
            iterations: 1,
            maxDuration: '10s',
            startTime: '4s',
            exec: 'shopPageFlow',
        },
        searchProductSmoke: {
            executor: 'per-vu-iterations',
            vus: 1,
            iterations: 1,
            maxDuration: '10s',
            startTime: '8s',
            exec: 'searchProductFlow',
        },
        singleProductSmoke: {
            executor: 'per-vu-iterations',
            vus: 1,
            iterations: 1,
            maxDuration: '10s',
            startTime: '12s',
            exec: 'singleProductFlow',
        },
        checkoutGuestSmoke: {
            executor: 'per-vu-iterations',
            vus: 1,
            iterations: 1,
            maxDuration: '50s',
            startTime: '16s',
            exec: 'checkoutGuestFlow',
        },
        checkoutCustomerLoginSmoke: {
            executor: 'per-vu-iterations',
            vus: 1,
            iterations: 1,
            maxDuration: '50s',
            startTime: '32s',
            exec: 'checkoutCustomerLoginFlow',
        },
        myAccountSmoke: {
            executor: 'per-vu-iterations',
            vus: 1,
            iterations: 1,
            maxDuration: '50s',
            startTime: '48s',
            exec: 'myAccountFlow',
        },
        cartSmoke: {
            executor: 'per-vu-iterations',
            vus: 1,
            iterations: 1,
            maxDuration: '50s',
            startTime: '58s',
            exec: 'cartFlow',
        },
        allMerchantSmoke: {
            executor: 'per-vu-iterations',
            vus: 1,
            iterations: 1,
            maxDuration: '360s',
            exec: 'allMerchantFlow',
        },
    },
	thresholds: {
		checks: [ 'rate==1' ],
	},
};

export function homePageFlow() {
    homePage();
}
export function shopPageFlow() {
    shopPage();
}
export function searchProductFlow() {
    searchProduct();
}
export function singleProductFlow() {
    singleProduct();
    categoryPage();
}
export function checkoutGuestFlow() {
    cart();
    checkoutGuest();
}
export function checkoutCustomerLoginFlow() {
    cart();
    checkoutCustomerLogin();
}
export function myAccountFlow() {
    myAccount();
}
export function cartFlow() {
    cartRemoveItem();
}
export function allMerchantFlow() {
    wpLogin();
    homeWCAdmin();
    orders();
    ordersSearch();
    products();
    addProduct();
}

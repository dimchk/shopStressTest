import http from 'k6/http';
import { check } from 'k6';

export let options = {
  insecureSkipTLSVerify: true,
  stages: [
    { duration: '10s', target: 600 },
    { duration: '3m', target: 600 },
    { duration: '10s', target: 0 },
  ],
  thresholds: {
    'http_req_duration{status:200}': ['max>=0'],
    'http_req_duration{status:400}': ['max>=0'],
    'http_req_duration{status:500}': ['max==0'],
    'http_req_duration{status:502}': ['max==0'],
    'http_req_duration{status:503}': ['max==0'],
    'http_req_duration{method:POST}': ['max>=0'],
  },
  'summaryTrendStats': ['count', 'min', 'med', 'avg', 'p(90)', 'p(95)', 'max'],
};

export default function () {
  const addToCartResponse = http.post('http://localhost/add-to-cart', JSON.stringify({ "productId": 1 }), {
    headers: {
      'Content-Type': 'application/json',
    },
  });

  check(addToCartResponse, {
    'Add to cart request successful': (response) => {
      return response.status === 200;
    },
  });

  check(addToCartResponse, {
    'Add to cart request status 400 and text - " Oops! It seems this product is currently out of stock."': (response) => {
      return response.status === 400 && response.body === "Oops! It seems this product is currently out of stock.";
    },
  });

  const checkoutResponse = http.post('http://localhost/checkout');
  check(checkoutResponse, {
    'Checkout request successful': (response) => {
      return response.status === 200;
    },
  });
  check(checkoutResponse, {
    'Checkout request status 400 and text - "Product not found or is out of stock"': (response) => {
      return response.status === 400 && response.body === "Product not found or is out of stock";
    },
  });
  check(checkoutResponse, {
    'Checkout request status 400 and text - "Cart is empty"': (response) => {
      return response.status === 400 && response.body === "Cart is empty";
    },
  });
}

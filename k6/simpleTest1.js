import http from 'k6/http';
import { check, group } from 'k6';

export default function () {
  group('Example API requests', function () {
    // Make a GET request
    const responseGet = http.get('http://test.k6.io');
    // Check the response
    check(responseGet, {
      'GET status is 200': (res) => res.status === 200,
    });

    // Print summary grouped by status code and text
    const summary = {};
    const results = {};
    results['GET'] = { [responseGet.status]: responseGet.statusText || 'No status text' };

    for (const [method, result] of Object.entries(results)) {
      for (const [status, text] of Object.entries(result)) {
        if (!summary[status]) {
          summary[status] = {};
        }
        if (!summary[status][text]) {
          summary[status][text] = 0;
        }
        summary[status][text]++;
      }
    }

    console.log('Summary:');
    for (const [status, texts] of Object.entries(summary)) {
      console.log(`Status: ${status}`);
      for (const [text, count] of Object.entries(texts)) {
        console.log(`  ${text}: ${count}`);
      }
    }
  });
}

#!/usr/bin/env python3
"""
Automated QA Test Script - Inventory Management System
Used to assist manual testing, automatically verify system basic functions
"""

import requests
import time
import json
from urllib.parse import urljoin
import sys

class InventorySystemTester:
    def __init__(self, base_url="http://localhost"):
        self.base_url = base_url
        self.session = requests.Session()
        self.test_results = []
        
    def log_test(self, test_id, description, result, details=""):
        """Record test results"""
        status = "PASS" if result else "FAIL"
        self.test_results.append({
            "test_id": test_id,
            "description": description,
            "status": status,
            "details": details
        })
        print(f"[{status}] {test_id}: {description}")
        if details:
            print(f"    Details: {details}")
    
    def test_server_connection(self):
        """Test server connection"""
        try:
            response = self.session.get(self.base_url, timeout=5)
            self.log_test("ENV-01", "Apache server connection", 
                          response.status_code == 200, 
                          f"Status Code: {response.status_code}")
        except Exception as e:
            self.log_test("ENV-01", "Apache server connection", False, str(e))
    
    def test_login_page_access(self):
        """Test login page access"""
        try:
            url = urljoin(self.base_url, "/login.php")
            response = self.session.get(url, timeout=5)
            has_login_form = "username" in response.text and "password" in response.text
            self.log_test("ENV-03", "Login page access", 
                          response.status_code == 200 and has_login_form,
                          f"Status Code: {response.status_code}, login form found: {has_login_form}")
        except Exception as e:
            self.log_test("ENV-03", "Login page access", False, str(e))
    
    def test_login_functionality(self):
        """Test login functionality"""
        try:
            # Test correct credentials
            url = urljoin(self.base_url, "/login.php")
            login_data = {
                "username": "example",
                "password": "123456"
            }
            response = self.session.post(url, data=login_data, allow_redirects=False)
            
            # Check redirect to dashboard
            is_redirect = response.status_code in [301, 302]
            redirect_location = response.headers.get('Location', '')
            self.log_test("BR2-02", "Valid credentials login", 
                          is_redirect and "dashboard.php" in redirect_location,
                          f"Redirect status: {response.status_code}, location: {redirect_location}")
            
            # Test invalid credentials
            wrong_data = {
                "username": "wrong",
                "password": "wrong"
            }
            response = self.session.post(url, data=wrong_data)
            has_error = "Invalid username or password" in response.text
            self.log_test("BR2-03", "Invalid credentials login", 
                          has_error and response.status_code == 200,
                          f"Error message shown: {has_error}")
            
        except Exception as e:
            self.log_test("BR2-02/03", "Login functionality test", False, str(e))
    
    def test_dashboard_access(self):
        """Test dashboard access"""
        try:
            # First, log in
            login_url = urljoin(self.base_url, "/login.php")
            login_data = {"username": "example", "password": "123456"}
            self.session.post(login_url, data=login_data)
            
            # Then access dashboard
            dashboard_url = urljoin(self.base_url, "/dashboard.php")
            response = self.session.get(dashboard_url)
            
            has_table = "Product ID" in response.text and "Product Name" in response.text
            has_search = "Search by Product ID" in response.text
            
            self.log_test("BR4-01", "Dashboard displays inventory table", 
                          response.status_code == 200 and has_table,
                          f"Status Code: {response.status_code}, table found: {has_table}")
            self.log_test("UR3-01", "Search box present", 
                          has_search,
                          f"Search box found: {has_search}")
            
        except Exception as e:
            self.log_test("BR4-01", "Dashboard access test", False, str(e))
    
    def test_search_functionality(self):
        """Test search functionality"""
        try:
            # Log in first
            login_url = urljoin(self.base_url, "/login.php")
            login_data = {"username": "example", "password": "123456"}
            self.session.post(login_url, data=login_data)
            
            # Search for existing product
            search_url = urljoin(self.base_url, "/dashboard.php?search=Camera")
            response = self.session.get(search_url)
            has_camera_results = "Camera" in response.text
            self.log_test("UR3-02", "Product name search", 
                          response.status_code == 200 and has_camera_results,
                          f"'Camera' in results: {has_camera_results}")
            
            # Search for non-existent product
            empty_search_url = urljoin(self.base_url, "/dashboard.php?search=NonExistentProduct")
            response = self.session.get(empty_search_url)
            no_results = "No records found" in response.text
            self.log_test("UR3-04", "No-results search", 
                          no_results,
                          f"No records message shown: {no_results}")
            
        except Exception as e:
            self.log_test("UR3-02/04", "Search functionality test", False, str(e))
    
    def test_performance(self):
        """Test page load performance"""
        try:
            # Log in first
            login_url = urljoin(self.base_url, "/login.php")
            login_data = {"username": "example", "password": "123456"}
            self.session.post(login_url, data=login_data)
            
            # Measure dashboard load time
            dashboard_url = urljoin(self.base_url, "/dashboard.php")
            start_time = time.time()
            response = self.session.get(dashboard_url)
            load_time = time.time() - start_time
            
            self.log_test("NFR2-01", "Page load performance", 
                          load_time < 2.0,
                          f"Load time: {load_time:.2f}s")
            
        except Exception as e:
            self.log_test("NFR2-01", "Performance test", False, str(e))
    
    def test_security_basics(self):
        """Test basic security features"""
        try:
            # Test access control for unauthenticated users
            dashboard_url = urljoin(self.base_url, "/dashboard.php")
            response = self.session.get(dashboard_url, allow_redirects=False)
            is_protected = (response.status_code in [301, 302] 
                            and "login.php" in response.headers.get('Location', ''))
            self.log_test("UR2-01", "Access control for dashboard", 
                          is_protected,
                          f"Redirect to login: {is_protected}")
            
            # Test simple SQL injection protection
            login_url = urljoin(self.base_url, "/login.php")
            injection_data = {
                "username": "' OR '1'='1",
                "password": "' OR '1'='1"
            }
            response = self.session.post(login_url, data=injection_data, allow_redirects=False)
            no_sql_injection = not (response.status_code in [301, 302] 
                                    and "dashboard.php" in response.headers.get('Location', ''))
            self.log_test("NFR4-01", "Basic SQL injection protection", 
                          no_sql_injection,
                          f"Injection blocked: {no_sql_injection}")
            
        except Exception as e:
            self.log_test("Security", "Security tests", False, str(e))
    
    def run_all_tests(self):
        """Run all tests"""
        print("=" * 60)
        print("Starting automated QA tests - Inventory Management System")
        print("=" * 60)
        
        # Environment tests
        print("\n--- Environment Connection Tests ---")
        self.test_server_connection()
        self.test_login_page_access()
        
        # Functional tests
        print("\n--- Functional Tests ---")
        self.test_login_functionality()
        self.test_dashboard_access()
        self.test_search_functionality()
        
        # Performance tests
        print("\n--- Performance Tests ---")
        self.test_performance()
        
        # Security tests
        print("\n--- Security Tests ---")
        self.test_security_basics()
        
        # Summary
        self.print_summary()
    
    def print_summary(self):
        """Print a summary of test results"""
        print("\n" + "=" * 60)
        print("Test Summary")
        print("=" * 60)
        
        total = len(self.test_results)
        passed = len([t for t in self.test_results if t["status"] == "PASS"])
        failed = total - passed
        
        print(f"Total tests: {total}")
        print(f"Passed: {passed}")
        print(f"Failed: {failed}")
        print(f"Pass rate: {passed/total*100:.1f}%")
        
        if failed:
            print("\nFailed tests:")
            for t in self.test_results:
                if t["status"] == "FAIL":
                    print(f"  - {t['test_id']}: {t['description']}")
                    if t["details"]:
                        print(f"    {t['details']}")
        
        # Save results to JSON
        with open('test_results.json', 'w', encoding='utf-8') as f:
            json.dump(self.test_results, f, ensure_ascii=False, indent=2)
        print("\nDetailed results saved to test_results.json")

def main():
    """Main entry point"""
    base_url = "http://localhost"
    if len(sys.argv) > 1:
        base_url = sys.argv[1]
    
    tester = InventorySystemTester(base_url)
    tester.run_all_tests()

if __name__ == "__main__":
    main()

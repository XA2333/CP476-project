# QA Test Script - Inventory Management System

## Test Information
- **Project Name**: CP476 Inventory Management System
- **Test Date**: July 8, 2025
- **Test Environment**: Windows + Apache + MySQL + PHP
- **Tester**: [Tester Name]

---

## 1. Environment Verification Test

### 1.1 Server Connection Test
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| ENV-01 | Access `http://localhost` in browser | Display Apache default page or project homepage | | |
| ENV-02 | Check MySQL service status | MySQL service running normally | | |
| ENV-03 | Access `http://localhost/login.php` | Display login page | | |

---

## 2. Functional Requirements Test (FR)

### 2.1 Business Requirements Test (BR)

#### BR-1: Create Inventory Table
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| BR1-01 | First time accessing system, check database table creation | Automatically create SupplierTable, ProductTable, InventoryTable | | |
| BR1-02 | Verify InventoryTable structure | Contains fields: ProductID, ProductName, Quantity, Price, Status, SupplierName | | |
| BR1-03 | Check table relationships | InventoryTable correctly combines Supplier and Product data | | |

#### BR-2: User Login Page
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| BR2-01 | Access `http://localhost/login.php` | Display login form (username, password fields) | | |
| BR2-02 | Enter correct credentials: username=`example`, password=`123456` | Redirect to dashboard.php | | |
| BR2-03 | Enter incorrect credentials | Display error message, stay on login page | | |
| BR2-04 | Submit empty login form | Display validation error | | |

#### BR-3: MySQL Database Storage
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| BR3-01 | Check if database `inventorydb` is created | Database exists | | |
| BR3-02 | Verify all required tables exist | SupplierTable, ProductTable, InventoryTable exist | | |
| BR3-03 | Check sample data insertion | Tables contain test data | | |

#### BR-4: Display Inventory Table on Web Page
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| BR4-01 | View homepage after successful login | Display inventory table | | |
| BR4-02 | Verify table column headers | Include: Product ID, Product Name, Quantity, Price ($), Status, Supplier, Actions | | |
| BR4-03 | Verify data display integrity | All records display correctly, no garbled text | | |

#### BR-5: Sort by Product ID in Ascending Order
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| BR5-01 | View inventory table data order | Data sorted by ProductID in ascending order | | |
| BR5-02 | Add new product then refresh page | New product inserted in correct position | | |

### 2.2 User Requirements Test (UR)

#### UR-1: Access Client Web Page
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| UR1-01 | Access system using Chrome browser | Page loads normally | | |
| UR1-02 | Access system using Firefox browser | Page loads normally | | |
| UR1-03 | Access system using Edge browser | Page loads normally | | |

#### UR-2: User Login Verification
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| UR2-01 | Access dashboard.php without login | Redirect to login.php | | |
| UR2-02 | Access protected pages after login | Normal access | | |
| UR2-03 | Click logout after login | Clear session, return to login page | | |

#### UR-3: Search Function
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| UR3-01 | Search ProductID "2591" | Display related product records | | |
| UR3-02 | Search ProductName "Camera" | Display all camera products | | |
| UR3-03 | Search SupplierName "Samsung" | Display Samsung supplier products | | |
| UR3-04 | Search for non-existent content | Display "No records found" | | |
| UR3-05 | Empty search | Display all records | | |
| UR3-06 | Click "Clear Search" | Clear search, display all records | | |

#### UR-4: Modify Existing Entries
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| UR4-01 | Click "Edit" button on a product | Open update modal with pre-filled data | | |
| UR4-02 | Use search function to select product | Search box displays matching results | | |
| UR4-03 | Modify quantity and submit | Data updated successfully, display success message | | |
| UR4-04 | Modify price and submit | Price updated successfully | | |
| UR4-05 | Modify status and submit | Status updated successfully | | |
| UR4-06 | Submit empty required fields | Display validation error | | |

#### UR-5: Delete Existing Entries
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| UR5-01 | Click "Delete" button | Display confirmation dialog | | |
| UR5-02 | Confirm deletion | Record deleted, display success message | | |
| UR5-03 | Cancel deletion | Record remains unchanged | | |

### 2.3 System Requirements Test (SR)

#### SR-1 & SR-2: Client-Server Connection
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| SR12-01 | Verify Apache-MySQL connection | Database operations execute normally | | |
| SR12-02 | Execute database read operations | Data displays correctly | | |
| SR12-03 | Execute database write operations | Data saved successfully | | |

#### SR-4: Search SQL Statements
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| SR4-01 | Check SQL statements in search.php | Use LIKE operator to search specified fields | | |
| SR4-02 | Verify SQL injection protection | Use prepared statements | | |

#### SR-5: Update SQL Statements
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| SR5-01 | Check SQL statements in update.php | Use prepared statements to update records | | |
| SR5-02 | Verify field update integrity | All editable fields can be updated | | |

#### SR-6: Delete SQL Statements
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| SR6-01 | Check SQL statements in delete.php | Use prepared statements to delete records | | |

#### SR-7: Table Join Function
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| SR7-01 | Verify InventoryTable data source | Data comes from JOIN of Supplier and Product tables | | |
| SR7-02 | Check sorting function | Sorted by ProductID in ascending order | | |

#### SR-8: Web Page Display Function
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| SR8-01 | Verify PHP database connection | Use PDO to connect to MySQL | | |
| SR8-02 | Check data binding | HTML correctly displays database content | | |

#### SR-9: Error Handling
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| SR9-01 | Simulate database connection error | Display friendly error message | | |
| SR9-02 | Input invalid data types | Display validation error | | |
| SR9-03 | Check exception handling code | Use try-catch blocks | | |

---

## 3. Non-Functional Requirements Test (NFR)

### NFR-1: Availability Test
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| NFR1-01 | Access system continuously for 1 hour | System remains responsive | | |
| NFR1-02 | Access after simulating server restart | System recovers quickly | | |

### NFR-2: Performance Test
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| NFR2-01 | Measure page load time | <2 seconds | | |
| NFR2-02 | Measure database query response time | <1 second | | |
| NFR2-03 | Test large dataset loading | Performance remains stable | | |

### NFR-3: Usability Test
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| NFR3-01 | New user first-time use test | Can complete basic operations without training | | |
| NFR3-02 | Interface intuitiveness test | Buttons and functions are easy to understand | | |
| NFR3-03 | Error message clarity test | Error messages help users understand problems | | |

### NFR-4: Security Test
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| NFR4-01 | SQL injection test | System protects against SQL injection attacks | | |
| NFR4-02 | XSS attack test | Input is properly escaped | | |
| NFR4-03 | Session management test | Sessions are properly managed and auto-expire | | |
| NFR4-04 | Password storage test | Passwords should be hashed (currently plain text, needs improvement) | | |

---

## 4. Boundary and Exception Testing

### 4.1 Input Validation Test
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| BND-01 | Input overly long product name (>255 characters) | Display validation error or truncate | | |
| BND-02 | Input negative quantity | Display validation error | | |
| BND-03 | Input negative price | Display validation error | | |
| BND-04 | Input special characters | Handle properly or display error | | |

### 4.2 Database Exception Test
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| EXC-01 | Operate after disconnecting database | Display friendly error message | | |
| EXC-02 | Insert duplicate ProductID | Handle conflict properly | | |
| EXC-03 | Delete non-existent record | Display appropriate message | | |

---

## 5. User Interface Testing

### 5.1 Responsive Design Test
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| UI-01 | Test at 1920x1080 resolution | Interface displays normally | | |
| UI-02 | Test at 1366x768 resolution | Interface adapts well | | |
| UI-03 | Browser zoom test (90%-110%) | Interface remains usable | | |

### 5.2 Interaction Test
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| INT-01 | Modal open/close test | Opens/closes normally, no JavaScript errors | | |
| INT-02 | Form validation real-time feedback | Shows instant prompts on input errors | | |
| INT-03 | Search autocomplete test | Search dropdown works normally | | |

---

## 6. Compatibility Testing

### 6.1 Browser Compatibility
| Test ID | Browser | Version | Status | Notes |
|---------|---------|---------|--------|-------|
| COMP-01 | Chrome | Latest | | |
| COMP-02 | Firefox | Latest | | |
| COMP-03 | Edge | Latest | | |
| COMP-04 | Safari | Latest | | |

---

## 7. Data Reset Testing

### 7.1 Reset Function Test
| Test ID | Test Steps | Expected Result | Actual Result | Status |
|---------|------------|-----------------|---------------|--------|
| RST-01 | Click "Reset Database" button | Navigate to reset_database.php | | |
| RST-02 | Execute database reset | All tables are dropped and recreated | | |
| RST-03 | Verify data after reset | Contains complete sample data | | |

---

## Test Summary

### Test Statistics
- **Total Test Cases**: [Auto-calculated]
- **Passed**: [ ]
- **Failed**: [ ]
- **Skipped**: [ ]
- **Pass Rate**: [ ]%

### Key Issues
1. [ ] Password storage security needs improvement (currently plain text)
2. [ ] Need to add more input validation
3. [ ] Consider adding user management functionality

### Improvement Recommendations
1. Implement password hashing storage
2. Add CSRF protection
3. Improve error logging
4. Add data backup functionality

### Test Conclusion
[ ] System meets all functional requirements
[ ] System meets all non-functional requirements
[ ] Recommended for production deployment
[ ] Need to fix critical issues before retesting

---

**Test Completion Date**: ___________
**Tester Signature**: ___________

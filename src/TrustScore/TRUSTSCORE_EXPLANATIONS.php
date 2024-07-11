<?php

/*
 * Dataset for scoring and explaining the trustscore
 * The actual logic will be implemented by parsers in the 
 * trustscore namespace or by the trustscore service directly.
 * This file actual defines the points scored in each case and 
 * gives out three levels of explanation.
 *
 * Author: Antonio Peter
 * Contact: antonio.peter.dev@gmail.com
 *
 * 
 * Explanation Array for Trust Score Criteria
 *
 * This file contains explanations for each criterion code used in calculating the trust score.
 * Explanations include the maximum possible score, basic explanation for the average person,
 * premium explanation with additional details, developer explanation with technical insights,
 * and detailed explanations providing in-depth information for each level.
 *
 * Fields:
 * - maxScore: Maximum possible score for the criterion.
 * - basicExplanation: User-friendly explanation for the average person.
 * - basicExplanationDetail: Detailed paragraph providing simiple information describing the problem in a non technical way
 * - premiumExplanation: Details for website owners with a focus on the importance of the criterion.
 * - premiumExplanationDetail: Detailed paragraph providing comprehensive information that website owners need to inform their webmasters to resolve the issue.
 * - devExplanation: Technical details for developers, providing insights into the logic.
 * - devExplanationDetail: Detailed paragraph providing comprehensive developer information.
 *
 */

namespace TrustScore;

class TRUSTSCORE_EXPLANATIONS
{

// Define explanations for each criterion code
public $CRITERION_DETAILS = [
    // WHOIS_EMAIL_EMAILPRESENT: Check if the WHOIS record contains a registrant email address
    'WHOIS_EMAIL_EMAILPRESENT' => [
        'score' => 10,
        'maxScore' => 10, // Maximum possible score for this criterion
        'basicExplanation' => "Contact email found in WHOIS record, enhancing transparency and communication.",
        'basicExplanationDetail' => "The presence of a contact email in the WHOIS record is crucial for building trust between users and the website owner. It enhances transparency, allowing users to reach out for inquiries or concerns.",
        'premiumExplanation' => "Presence of a contact email fosters trust and accountability. Consider encouraging users to reach out and engage with the website.",
        'premiumExplanationDetail' => "The presence of a contact email in the WHOIS record fosters trust and accountability. It allows users to reach out and engage with the website, enhancing the overall user experience.",
        'devExplanation' => "Check for a registrant email address in the WHOIS record. Missing email may be due to privacy protection measures or incomplete data. Ensure accurate WHOIS info for transparency.",
        'devExplanationDetail' => "To calculate the trust score, the system checks for the presence of a registrant email address in the WHOIS record. If the email is missing, it may be due to privacy protection measures or incomplete data. Webmasters should ensure accurate WHOIS information for transparency.",
    ],

    // WHOIS_EMAIL_NOEMAIL: Check if the WHOIS record lacks a registrant email address
    'WHOIS_EMAIL_NOEMAIL' => [
        'score' => 0,
        'maxScore' => 10,
        'basicExplanation' => "No contact email in WHOIS record may limit communication and transparency.",
        'basicExplanationDetail' => "The absence of a contact email in the WHOIS record raises concerns about limited communication channels and transparency.",
        'premiumExplanation' => "Lack of a contact email is a concern for transparency. Encourage updating WHOIS with a valid contact email. Consider implementing alternative communication mechanisms.",
        'premiumExplanationDetail' => "The lack of a contact email in the WHOIS record is a concern for transparency. Webmasters are encouraged to update their WHOIS information with a valid contact email and consider implementing alternative communication mechanisms.",
        'devExplanation' => "Check for a registrant email address in the WHOIS record. Missing email may be due to privacy protection measures or incomplete data. Implement mechanisms for communication while protecting privacy.",
        'devExplanationDetail' => "To calculate the trust score, the system checks for the presence of a registrant email address in the WHOIS record. If the email is missing, it may be due to privacy protection measures or incomplete data. Webmasters should implement alternative communication mechanisms while respecting privacy concerns.",
    ],

    // SSL_LEVEL_NOSSL: Check if the site has no SSL certificate
    'SSL_LEVEL_NOSSL' => [
        'score' => -100,
        'maxScore' => 20, 
        'basicExplanation' => "No SSL certificate detected! Data exchanged with this website is not private!",
        'basicExplanationDetail' => "The absence of an SSL certificate on the website poses a significant security risk. Without SSL, data exchanged between your browser and the website is not encrypted, making it vulnerable to potential attackers.",
        'premiumExplanation' => "No SSL certificate detected! Data exchanged with your website can be intercepted by malicious actors",
        'premiumExplanationDetail' => "SSL (Secure Socket Layer) is essential for securing user data and building trust. The absence of an SSL certificate puts user information at risk. Webmasters are strongly advised to obtain and implement an SSL certificate immediately to ensure a secure connection.",
        'devExplanation' => "No SSL certificate detected! Verify that site has and enforces SSL encryption!",
        'devExplanationDetail' => "To calculate the trust score, the system checks if the website has an SSL certificate installed. The absence of SSL exposes user data to potential threats. Webmasters should obtain and install an SSL certificate to encrypt data and establish a secure connection, ensuring the safety of user information.",
    ],

    // SSL_LEVEL_FREESSL: Check if the site has a free SSL certificate
    'SSL_LEVEL_FREESSL' => [
        'score' => 0,
        'maxScore' => 20, 
        'basicExplanation' => "Free SSL certificate detected. While functional, free SSL certificates may be set up by malicious actors.",
        'basicExplanationDetail' => "The presence of a free SSL certificate indicates that the website uses encryption, which is positive. However, it's important to note that free SSL certificates can be easily obtained by malicious actors, potentially compromising the security of the website.",
        'premiumExplanation' => "SSL encryption is present, but the certificate was issued by a free SSL provider. While functional, exercise caution as these certificates can be set up by malicious actors.",
        'premiumExplanationDetail' => "The website has SSL encryption, which is a positive aspect. However, the SSL certificate being free raises concerns, as such certificates can easily be set up by anyone, including malicious actors. Webmasters should exercise caution and consider upgrading to a paid SSL certificate.",
        'devExplanation' => "Check the SSL certificate provider. A free SSL certificate is detected. Exercise caution and consider upgrading to a more secure certificate.",
        'devExplanationDetail' => "To calculate the trust score, the system checks the SSL certificate provider. If a free SSL certificate is detected, webmasters should exercise caution, as these certificates can be set up by anyone and are less valued than paid certificates. Consider upgrading to a paid SSL certificate for enhanced integrity.",
    ],

    // SSL_LEVEL_DVSSL: Check if the site has a Domain Validated (DV) SSL certificate
    'SSL_LEVEL_DVSSL' => [
        'score' => 10,
        'maxScore' => 20, 
        'basicExplanation' => "Domain Validated (DV) SSL certificate detected. Offers encryption but lacks the same integrity validation as Organization Validated (OV) certificates.",
        'basicExplanationDetail' => "The presence of a Domain Validated (DV) SSL certificate indicates that the website uses encryption, providing security. However, DV certificates lack the same integrity validation as Organization Validated (OV) certificates and are linked to a registered company or organization.",
        'premiumExplanation' => "DV SSL certificate detected. While offering encryption, DV certificates lack the same integrity validation as OV certificates. Consider upgrading for enhanced trust.",
        'premiumExplanationDetail' => "The website has a Domain Validated (DV) SSL certificate, providing encryption for secure communication. However, it's important to note that DV certificates lack the same integrity validation as Organization Validated (OV) certificates. Webmasters are encouraged to consider upgrading to an OV certificate for enhanced trust.",
        'devExplanation' => "Check the SSL certificate type. A Domain Validated (DV) SSL certificate is detected. DV certificates provide encryption but lack the same integrity validation as OV certificates.",
        'devExplanationDetail' => "To calculate the trust score, the system checks the SSL certificate type. If a Domain Validated (DV) SSL certificate is detected, webmasters should be aware that DV certificates provide encryption but lack the same integrity validation as Organization Validated (OV) certificates. Consider upgrading to an OV certificate for enhanced trust and security.",
    ],

    // SSL_LEVEL_OVSSL: Check if the site has an Organization Validated (OV) SSL certificate
    'SSL_LEVEL_OVSSL' => [
        'score' => 20,
        'maxScore' => 20, 
        'basicExplanation' => "Organization Validated (OV) SSL certificate detected. Offers strong security with validated information linked to a company or organization.",
        'basicExplanationDetail' => "The presence of an Organization Validated (OV) SSL certificate indicates that the website uses encryption with strong security. OV certificates are linked to a company or organization, providing validated information.",
        'premiumExplanation' => "OV SSL certificate detected. Offers the highest level of security with validated information linked to a company or organization.",
        'premiumExplanationDetail' => "The website has an Organization Validated (OV) SSL certificate, offering one of the highest levels of security. OV certificates are linked to a company or organization, providing validated information for enhanced trust.",
        'devExplanation' => "Check the SSL certificate type. An Organization Validated (OV) SSL certificate is detected. OV certificates offer one of the highest levels of security with validated information linked to a company or organization.",
        'devExplanationDetail' => "To calculate the trust score, the system checks the SSL certificate type. If an Organization Validated (OV) SSL certificate is detected, webmasters can be assured of the highest level of security. OV certificates are linked to a company or organization, providing validated information for enhanced trust and integrity.",
    ],

    // WHOIS_GUARD_TRUE: Check if the website uses a WHOIS privacy guard
    'WHOIS_GUARD_TRUE' => [
        'score' => 0,
        'maxScore' => 10,
        'basicExplanation' => "WHOIS privacy guard enabled. Registrant information is not publicly accessible.",
        'basicExplanationDetail' => "The website has enabled a WHOIS privacy guard, which prevents public access to registrant information. While this protects the registrant's privacy, it may affect transparency.",
        'premiumExplanation' => "WHOIS privacy guard enabled. While protecting privacy, transparency may be compromised.",
        'premiumExplanationDetail' => "The website has enabled a WHOIS privacy guard to protect the registrant's privacy. However, this may compromise transparency, as public access to registrant information is restricted. If you are a operating publicly you should consider disabling the privacy guard in order to show your company information in the whois registry.",
        'devExplanation' => "Check if the website has enabled a WHOIS privacy guard. If true, registrant information is not publicly accessible.",
        'devExplanationDetail' => "To calculate the trust score, the system checks if the website has enabled a WHOIS privacy guard. If true, registrant information is not publicly accessible, which may affect transparency. This is penalized with a score of 0 in this category",
    ],

    // WHOIS_GUARD_FALSE: Check if the website does not use a WHOIS privacy guard
    'WHOIS_GUARD_FALSE' => [
        'score' => 10,
        'maxScore' => 10,
        'basicExplanation' => "No WHOIS privacy guard detected. Registrant information is publicly accessible.",
        'basicExplanationDetail' => "The website does not seem to use a WHOIS privacy guard, allowing public access to registrant information. This transparency enhances trustworthiness.",
        'premiumExplanation' => "No WHOIS privacy guard detected. Transparent access to registrant information enhances trust.",
        'premiumExplanationDetail' => "The website does not use a WHOIS privacy guard, allowing transparent access to registrant information. This transparency enhances trustworthiness and credibility.",
        'devExplanation' => "Check if the website has enabled a WHOIS privacy guard. If false, registrant information is publicly accessible.",
        'devExplanationDetail' => "To calculate the trust score, the system checks if the website has enabled a WHOIS privacy guard. If false, registrant information is publicly accessible, enhancing transparency and trustworthiness.",
    ],

    // WHOIS_GUARD_UNKNOWN: Check if it's uncertain whether the website uses WHOIS privacy guard
    'WHOIS_GUARD_UNKNOWN' => [
        'score' => -1,
        'maxScore' => 10,
        'basicExplanation' => "Could not verify if this site uses WHOIS privacy guard.",
        'basicExplanationDetail' => "It's uncertain whether this site uses WHOIS privacy guard to protect registrant information.",
        'premiumExplanation' => "Could not verify if this site uses WHOIS privacy guard.",
        'premiumExplanationDetail' => "It's uncertain whether this site uses WHOIS privacy guard to protect registrant information. WHOIS privacy guard is important for safeguarding personal information and preventing spam.",
        'devExplanation' => "Unable to verify if the site uses WHOIS privacy guard. Consider checking the WHOIS record for privacy guard information.",
        'devExplanationDetail' => "To verify if the site uses WHOIS privacy guard, check the WHOIS record for privacy guard information. WHOIS privacy guard protects registrant information from public view, enhancing privacy and security.",
    ],


    // WHOIS_DOMAINAGE_UNKNOWN: Check if the website's domain age is unknown
    'WHOIS_DOMAINAGE_UNKNOWN' => [
        'score' => 0,
        'maxScore' => 10,
        'basicExplanation' => "Could not determine the age of this domain.",
        'basicExplanationDetail' => "The age of this domain could not be determined, leaving uncertainty about its trustworthiness.",
        'premiumExplanation' => "Could not determine the age of this domain.",
        'premiumExplanationDetail' => "The age of this domain could not be determined, leaving uncertainty about its trustworthiness. Domain age is an important factor in assessing a website's credibility and trustworthiness.",
        'devExplanation' => "Unable to determine the age of the domain. Consider checking the WHOIS record for creation date information.",
        'devExplanationDetail' => "To determine the age of the domain, check the WHOIS record for creation date information. Domain age is an important factor in assessing a website's credibility and trustworthiness.",
    ],

    // WHOIS_DOMAINAGE_7DAYS: Check if the domain is less than 7 days old
    'WHOIS_DOMAINAGE_7DAYS' => [
        'score' => -100,
        'maxScore' => 10,
        'basicExplanation' => "This site is less than 7 days old! Be careful!",
        'basicExplanationDetail' => "The website is less than 7 days old, indicating it is newly registered. Exercise caution when interacting with such websites.",
        'premiumExplanation' => "This site is less than 7 days old, indicating it is newly registered. Be cautious of newly established websites.",
        'premiumExplanationDetail' => "The website is less than 7 days old, suggesting it is newly registered. Newly established websites may lack a reputation or history, so exercise caution when interacting with them.",
        'devExplanation' => "Check the domain registration date. If the domain is less than 7 days old, consider it newly registered.",
        'devExplanationDetail' => "To calculate the trust score, the system checks the domain registration date. If the domain is less than 7 days old, it is considered newly registered. Webmasters should be cautious when dealing with newly established websites.",
    ],

    // WHOIS_DOMAINAGE_MONTH: Check if the domain is less than a month old
    'WHOIS_DOMAINAGE_MONTH' => [
        'score' => 1,
        'maxScore' => 10,
        'basicExplanation' => "This site is less than a month old! Be careful!",
        'basicExplanationDetail' => "The website is less than a month old, indicating it is newly registered. Exercise caution when interacting with such websites.",
        'premiumExplanation' => "This site is less than a month old, indicating it is newly registered. Be cautious of newly established websites.",
        'premiumExplanationDetail' => "The website is less than a month old, suggesting it is newly registered. Newly established websites may lack a reputation or history, so exercise caution when interacting with them.",
        'devExplanation' => "Check the domain registration date. If the domain is less than a month old, consider it newly registered.",
        'devExplanationDetail' => "To calculate the trust score, the system checks the domain registration date. If the domain is less than a month old, it is considered newly registered. Webmasters should be cautious when dealing with newly established websites.",
    ],

    // WHOIS_DOMAINAGE_SIXMONTHS: Check if the domain is less than 6 months old
    'WHOIS_DOMAINAGE_SIXMONTHS' => [
        'score' => 2,
        'maxScore' => 10,
        'basicExplanation' => "This site is less than 6 months old! Be careful!",
        'basicExplanationDetail' => "The website is less than 6 months old, indicating it is relatively new. Exercise caution when interacting with such websites.",
        'premiumExplanation' => "This site is less than 6 months old, indicating it is relatively new. Be cautious of newly established websites.",
        'premiumExplanationDetail' => "The website is less than 6 months old, suggesting it is relatively new. Newly established websites may lack a reputation or history, so exercise caution when interacting with them.",
        'devExplanation' => "Check the domain registration date. If the domain is less than 6 months old, consider it relatively new.",
        'devExplanationDetail' => "To calculate the trust score, the system checks the domain registration date. If the domain is less than 6 months old, it is considered relatively new. Webmasters should be cautious when dealing with newly established websites.",
    ],

    // WHOIS_DOMAINAGE_OVER1YEAR: Check if the domain is over 1 year old
    'WHOIS_DOMAINAGE_OVER1YEAR' => [
        'score' => 5,
        'maxScore' => 10,
        'basicExplanation' => "This domain is over a year old.",
        'basicExplanationDetail' => "The website is over a year old, indicating it has some history and stability.",
        'premiumExplanation' => "This domain is over a year old, indicating it has some history and stability.",
        'premiumExplanationDetail' => "The website is over a year old, suggesting it has some history and stability. Established websites are often more trustworthy.",
        'devExplanation' => "Check the domain registration date. If the domain is over a year old, consider it established.",
        'devExplanationDetail' => "To calculate the trust score, the system checks the domain registration date. If the domain is over a year old, it is considered established. Established websites are often more trustworthy.",
    ],

    // WHOIS_DOMAINAGE_OVER2YEARS: Check if the domain is over 2 years old
    'WHOIS_DOMAINAGE_OVER2YEARS' => [
        'score' => 6,
        'maxScore' => 10,
        'basicExplanation' => "This domain is over 2 years old.",
        'basicExplanationDetail' => "The website is over 2 years old, indicating it has some history and stability.",
        'premiumExplanation' => "This domain is over 2 years old, indicating it has some history and stability.",
        'premiumExplanationDetail' => "The website is over 2 years old, suggesting it has some history and stability. Established websites are often more trustworthy.",
        'devExplanation' => "Check the domain registration date. If the domain is over 2 years old, consider it established.",
        'devExplanationDetail' => "To calculate the trust score, the system checks the domain registration date. If the domain is over 2 years old, it is considered established. Established websites are often more trustworthy.",
    ],

    // WHOIS_DOMAINAGE_OVER_3YEARS: Check if the domain is over 3 years old
    'WHOIS_DOMAINAGE_OVER3YEARS' => [
        'score' => 7,
        'maxScore' => 10,
        'basicExplanation' => "This domain is over 3 years old.",
        'basicExplanationDetail' => "The website is over 3 years old, indicating it has a significant history and stability.",
        'premiumExplanation' => "This domain is over 3 years old, indicating it has a significant history and stability.",
        'premiumExplanationDetail' => "The website is over 3 years old, suggesting it has a significant history and stability. Established websites are often more trustworthy.",
        'devExplanation' => "Check the domain registration date. If the domain is over 3 years old, consider it well-established.",
        'devExplanationDetail' => "To calculate the trust score, the system checks the domain registration date. If the domain is over 3 years old, it is considered well-established. Well-established websites are often more trustworthy.",
    ],

    // WHOIS_DOMAINAGE_OVER4YEARS: Check if the domain is over 4 years old
    'WHOIS_DOMAINAGE_OVER4YEARS' => [
        'score' => 8,
        'maxScore' => 10,
        'basicExplanation' => "This domain is over 4 years old.",
        'basicExplanationDetail' => "The website is over 4 years old, indicating it has a significant history and stability.",
        'premiumExplanation' => "This domain is over 4 years old, indicating it has a significant history and stability.",
        'premiumExplanationDetail' => "The website is over 4 years old, suggesting it has a significant history and stability. Established websites are often more trustworthy.",
        'devExplanation' => "Check the domain registration date. If the domain is over 4 years old, consider it well-established.",
        'devExplanationDetail' => "To calculate the trust score, the system checks the domain registration date. If the domain is over 4 years old, it is considered well-established. Well-established websites are often more trustworthy.",
    ],

    // WHOIS_DOMAINAGE_OVER5YEARS: Check if the domain is over 5 years old
    'WHOIS_DOMAINAGE_OVER5YEARS' => [
        'score' => 9,
        'maxScore' => 10,
        'basicExplanation' => "This domain is over 5 years old.",
        'basicExplanationDetail' => "The website is over 5 years old, indicating it has a significant history and stability.",
        'premiumExplanation' => "This domain is over 5 years old, indicating it has a significant history and stability.",
        'premiumExplanationDetail' => "The website is over 5 years old, suggesting it has a significant history and stability. Established websites are often more trustworthy.",
        'devExplanation' => "Check the domain registration date. If the domain is over 5 years old, consider it well-established.",
        'devExplanationDetail' => "To calculate the trust score, the system checks the domain registration date. If the domain is over 5 years old, it is considered well-established. Well-established websites are often more trustworthy.",
    ],


    // WHOIS_DOMAINAGE_OVER6YEARS: Check if the domain is over 6 years old
    'WHOIS_DOMAINAGE_OVER6YEARS' => [
        'score' => 10,
        'maxScore' => 10,
        'basicExplanation' => "This domain is over 6 years old.",
        'basicExplanationDetail' => "The website is over 6 years old, indicating it has been established for a significant period. Established websites often have a strong reputation and history.",
        'premiumExplanation' => "This domain is over 6 years old, indicating it is well-established. Established websites often have a strong reputation and history.",
        'premiumExplanationDetail' => "The website is over 6 years old, suggesting it is well-established. Established websites often have a strong reputation and history, making them more trustworthy.",
        'devExplanation' => "Check the domain registration date. If the domain is over 6 years old, consider it well-established.",
        'devExplanationDetail' => "To calculate the trust score, the system checks the domain registration date. If the domain is over 6 years old, it is considered well-established. Well-established websites often have a strong reputation and history.",
    ],

    // ABUSEIPDB_HIGHSCORE: Check if the abuse confidence score is above the threshold
    'ABUSEIPDB_HIGHSCORE' => [
        'score' => -100,
        'maxScore' => 10,
        'basicExplanation' => "This site has a high abuse confidence score, indicating numerous reports on abuse databases and potentially more dangerous activity!",
        'basicExplanationDetail' => "The website has a high abuse confidence score, suggesting numerous reports on abuse databases and potentially dangerous activity. Exercise extreme caution when interacting with such websites.",
        'premiumExplanation' => "This site has a high abuse confidence score, indicating numerous reports on abuse databases and potentially more dangerous activity.",
        'premiumExplanationDetail' => "The website has a high abuse confidence score, suggesting numerous reports on abuse databases and potentially more dangerous activity. Exercise extreme caution when interacting with such websites.",
        'devExplanation' => "Check the abuse confidence score. If it is above the threshold, exercise extreme caution.",
        'devExplanationDetail' => "To calculate the trust score, the system checks the abuse confidence score. If it is above the threshold, exercise extreme caution when interacting with the website.",
    ],

    // ABUSEIPDB_REPORTS: Check if there are reports on abuse databases
    'ABUSEIPDB_REPORTS' => [
        'score' => 0,
        'maxScore' => 10,
        'basicExplanation' => "This site has reports on abuse databases! Be careful!",
        'basicExplanationDetail' => "The website has reports on abuse databases, indicating potential malicious activity. Exercise caution when interacting with such websites.",
        'premiumExplanation' => "This site has reports on abuse databases, indicating potential malicious activity.",
        'premiumExplanationDetail' => "The website has reports on abuse databases, indicating potential malicious activity. Exercise caution when interacting with such websites.",
        'devExplanation' => "Check for reports on abuse databases. If present, exercise caution.",
        'devExplanationDetail' => "To calculate the trust score, the system checks for reports on abuse databases. If present, exercise caution when interacting with the website.",
    ],

    // ABUSEIPDB_DANGEROUSREPORTS: Check if there are numerous dangerous reports on abuse databases
    'ABUSEIPDB_DANGEROUSREPORTS' => [
        'score' => -100,
        'maxScore' => 10,
        'basicExplanation' => "This site has numerous dangerous reports on abuse databases!",
        'basicExplanationDetail' => "The website has numerous dangerous reports on abuse databases, indicating high-risk activity. Avoid interacting with such websites.",
        'premiumExplanation' => "This site has numerous dangerous reports on abuse databases, indicating high-risk activity.",
        'premiumExplanationDetail' => "The website has numerous dangerous reports on abuse databases, indicating high-risk activity. Avoid interacting with such websites.",
        'devExplanation' => "Check for numerous dangerous reports on abuse databases. If present, avoid interacting with the website.",
        'devExplanationDetail' => "To calculate the trust score, the system checks for numerous dangerous reports on abuse databases. If present, avoid interacting with the website.",
    ],

    // ABUSEIPDB_NO_REPORTS: Check if there are no abuse reports
    'ABUSEIPDB_NOREPORTS' => [
        'score' => 10,
        'maxScore' => 10,
        'basicExplanation' => "There are currently no abuse reports available to us that meet the criteria for this website to be labeled as untrustworthy.",
        'basicExplanationDetail' => "There are currently no abuse reports available to us that meet the criteria for this website to be labeled as untrustworthy.",
        'premiumExplanation' => "There are currently no abuse reports available to us that meet the criteria for this website to be labeled as untrustworthy.",
        'premiumExplanationDetail' => "There are currently no abuse reports available to us that meet the criteria for this website to be labeled as untrustworthy.",
        'devExplanation' => "Check for abuse reports. If there are none, consider the website trustworthy.",
        'devExplanationDetail' => "To calculate the trust score, the system checks for abuse reports. If there are none, consider the website trustworthy.",
    ],

    // ABUSEIPDB_FAILED_CHECK: Check if the abuse database check failed
    'ABUSEIPDB_FAILED_CHECK' => [
        'score' => -1,
        'maxScore' => 10,
        'basicExplanation' => "Failed to perform abuse database check.",
        'basicExplanationDetail' => "Failed to perform abuse database check. There is seems to be no record of this site in the database. Exercise caution when interacting with the website.",
        'premiumExplanation' => "Failed to perform abuse database check.",
        'premiumExplanationDetail' => "Failed to perform abuse database check. There is seems to be no record of this site in the database. Exercise caution when interacting with the website.",
        'devExplanation' => "Failed to perform abuse database check.",
        'devExplanationDetail' => "Failed to perform abuse database check. There is seems to be no record of this site in the database. Exercise caution when interacting with the website.",
    ],

    // HEADERS_CSP_HASCSP: Check if the site has a Content Security Policy
    'HEADERS_CSP_HASCSP' => [
        'score' => 5,
        'maxScore' => 5,
        'basicExplanation' => "This site has a Content Security Policy (CSP) in place, enhancing security.",
        'basicExplanationDetail' => "A Content Security Policy (CSP) is a security standard that helps prevent various types of attacks, such as cross-site scripting (XSS) and data injection attacks, by specifying approved sources of content that the browser can load. Having a CSP in place enhances the security of the website.",
        'premiumExplanation' => "This site has a Content Security Policy (CSP) in place, enhancing security.",
        'premiumExplanationDetail' => "A Content Security Policy (CSP) is a security standard that helps prevent various types of attacks, such as cross-site scripting (XSS) and data injection attacks, by specifying approved sources of content that the browser can load. Implementing a CSP is important for protecting user data and preventing unauthorized access to sensitive information. Website owners can implement a CSP by configuring the appropriate HTTP headers in their web server or using meta tags in their HTML.",
        'devExplanation' => "Check if the site has a Content Security Policy (CSP) configured in its HTTP headers or meta tags. If yes, it enhances security by preventing various types of attacks.",
        'devExplanationDetail' => "To implement a Content Security Policy (CSP), website owners can configure the appropriate HTTP headers in their web server or use meta tags in their HTML. The CSP specifies approved sources of content, such as scripts, stylesheets, and images, that the browser can load, helping prevent various types of attacks, such as cross-site scripting (XSS) and data injection attacks.",
    ],

    // HEADERS_CSP_NOCSP: Check if the site does not have a Content Security Policy
    'HEADERS_CSP_NOCSP' => [
        'score' => 0,
        'maxScore' => 5,
        'basicExplanation' => "This site does not have a Content Security Policy (CSP) in place.",
        'basicExplanationDetail' => "The website does not have a Content Security Policy (CSP) in place, which may leave it vulnerable to various types of attacks, such as cross-site scripting (XSS) and data injection attacks.",
        'premiumExplanation' => "This site does not have a Content Security Policy (CSP) in place.",
        'premiumExplanationDetail' => "A Content Security Policy (CSP) is an important security standard that helps prevent various types of attacks, such as cross-site scripting (XSS) and data injection attacks. The absence of a CSP leaves the website vulnerable to these attacks, increasing the risk of unauthorized access to sensitive information.",
        'devExplanation' => "Check if the site has a Content Security Policy (CSP) configured in its HTTP headers or meta tags. If not, consider implementing one to enhance security.",
        'devExplanationDetail' => "To enhance security, website owners should implement a Content Security Policy (CSP) by configuring the appropriate HTTP headers in their web server or using meta tags in their HTML. A CSP specifies approved sources of content that the browser can load, helping prevent various types of attacks, such as cross-site scripting (XSS) and data injection attacks.",
    ],

    // DNS_PHISHING_DMARC: Check if the site has DMARC protection
    'DNS_PHISHING_DMARC' => [
        'score' => 15,
        'maxScore' => 15,
        'basicExplanation' => "This site has DMARC protection.",
        'basicExplanationDetail' => "DMARC (Domain-based Message Authentication, Reporting, and Conformance) is an email authentication protocol that helps protect against phishing and email spoofing attacks. Having DMARC protection in place enhances the security of email communication.",
        'premiumExplanation' => "This site has DMARC protection.",
        'premiumExplanationDetail' => "DMARC (Domain-based Message Authentication, Reporting, and Conformance) is an email authentication protocol that helps protect against phishing and email spoofing attacks. Implementing DMARC protection ensures that only authorized senders can send emails from the domain, enhancing email security and trustworthiness.",
        'devExplanation' => "Check if the site has a DMARC DNS record. If yes, it has DMARC protection.",
        'devExplanationDetail' => "To determine if the site has DMARC protection, check if it has a DMARC DNS record configured. DMARC (Domain-based Message Authentication, Reporting, and Conformance) is an email authentication protocol that helps protect against phishing and email spoofing attacks by specifying policies for email validation and reporting.",
    ],

    // DNS_PHISHING_SPF: Check if the site has SPF protection
    'DNS_PHISHING_SPF' => [
        'score' => 15,
        'maxScore' => 15,
        'basicExplanation' => "This site has SPF protection.",
        'basicExplanationDetail' => "SPF (Sender Policy Framework) is an email authentication method that helps prevent email spoofing by specifying which mail servers are allowed to send emails on behalf of the domain. Having SPF protection in place enhances email security.",
        'premiumExplanation' => "This site has SPF protection.",
        'premiumExplanationDetail' => "SPF (Sender Policy Framework) is an email authentication method that helps prevent email spoofing by specifying which mail servers are allowed to send emails on behalf of the domain. Implementing SPF protection enhances email security and helps prevent phishing attacks.",
        'devExplanation' => "Check if the site has an SPF TXT record. If yes, it has SPF protection.",
        'devExplanationDetail' => "To determine if the site has SPF protection, check if it has an SPF TXT record configured. SPF (Sender Policy Framework) is an email authentication method that helps prevent email spoofing by specifying which mail servers are allowed to send emails on behalf of the domain.",
    ],

    // DNS_PHISHING_NONE: Check if the site has neither DMARC nor SPF protection
    'DNS_PHISHING_NONE' => [
        'score' => 0,
        'maxScore' => 15,
        'basicExplanation' => "No DMARC or SPF protection found!",
        'basicExplanationDetail' => "The website does not have DMARC or SPF protection configured, which may leave it vulnerable to phishing attacks and email spoofing.",
        'premiumExplanation' => "No DMARC or SPF protection found!",
        'premiumExplanationDetail' => "The website does not have DMARC or SPF protection configured, which may leave it vulnerable to phishing attacks and email spoofing. Implementing email authentication protocols such as DMARC and SPF enhances email security and helps prevent unauthorized email activity.",
        'devExplanation' => "Check if the site has DMARC or SPF protection configured. If not, consider implementing one or both to enhance email security.",
        'devExplanationDetail' => "To enhance email security, website owners should consider implementing email authentication protocols such as DMARC and SPF. DMARC (Domain-based Message Authentication, Reporting, and Conformance) and SPF (Sender Policy Framework) help prevent phishing attacks and email spoofing by verifying the authenticity of incoming emails.",
    ],

    // HEADERS_HTTPSECURITY_HEADERS_ALL: Check if all security headers are present
    'HEADERS_HTTPSECURITY_HEADERS_ALL' => [
        'score' => 5,
        'maxScore' => 5,
        'basicExplanation' => "This site has all the required security headers: Strict-Transport-Security, X-Content-Type-Options, X-Frame-Options, X-XSS-Protection!",
        'basicExplanationDetail' => "All required security headers, including Strict-Transport-Security, X-Content-Type-Options, X-Frame-Options, and X-XSS-Protection, are present on the website, enhancing its security posture.",
        'premiumExplanation' => "This site has all the required security headers: Strict-Transport-Security, X-Content-Type-Options, X-Frame-Options, X-XSS-Protection.",
        'premiumExplanationDetail' => "All required security headers, including Strict-Transport-Security, X-Content-Type-Options, X-Frame-Options, and X-XSS-Protection, are present on the website. These headers help protect against various types of attacks, such as clickjacking and cross-site scripting (XSS), enhancing the security of the website and ensuring a safer browsing experience for users.",
        'devExplanation' => "Check if all required security headers (Strict-Transport-Security, X-Content-Type-Options, X-Frame-Options, X-XSS-Protection) are present on the website. If yes, it enhances security.",
        'devExplanationDetail' => "To enhance security, ensure that all required security headers, including Strict-Transport-Security, X-Content-Type-Options, X-Frame-Options, and X-XSS-Protection, are present on the website. These headers help protect against various types of attacks, such as clickjacking and cross-site scripting (XSS), enhancing the security of the website and ensuring a safer browsing experience for users.",
    ],

    // HEADERS_HTTPSECURITY_HEADERS_SOME: Check if some security headers are present
    'HEADERS_HTTPSECURITY_HEADERS_SOME' => [
        'score' => 2.5,
        'maxScore' => 5,
        'basicExplanation' => "This site has some security headers: [Present Headers] - Missing: [Missing Headers].",
        'basicExplanationDetail' => "Some security headers, including [Present Headers], are present on the website, but others, such as [Missing Headers], are missing.",
        'premiumExplanation' => "This site has some security headers: [Present Headers] - Missing: [Missing Headers].",
        'premiumExplanationDetail' => "Some security headers, including [Present Headers], are present on the website, but others, such as [Missing Headers], are missing. Implementing all required security headers enhances the security of the website and protects against various types of attacks.",
        'devExplanation' => "Check if some security headers are present on the website. If yes, consider implementing all required security headers for enhanced security.",
        'devExplanationDetail' => "To enhance security, check if some security headers, including [Present Headers], are present on the website. Consider implementing all required security headers, including [Missing Headers], to protect against various types of attacks and ensure a safer browsing experience for users.",
    ],

    // HEADERS_HTTPSECURITY_HEADERS_NONE: Check if no security headers are present
    'HEADERS_HTTPSECURITY_HEADERS_NONE' => [
        'score' => 0,
        'maxScore' => 5,
        'basicExplanation' => "No security headers found - Missing: Strict-Transport-Security, X-Content-Type-Options, X-Frame-Options, X-XSS-Protection",
        'basicExplanationDetail' => "No security headers, including Strict-Transport-Security, X-Content-Type-Options, X-Frame-Options, and X-XSS-Protection, are present on the website, leaving it vulnerable to various types of attacks.",
        'premiumExplanation' => "No security headers found - Missing: Strict-Transport-Security, X-Content-Type-Options, X-Frame-Options, X-XSS-Protection",
        'premiumExplanationDetail' => "No security headers, including Strict-Transport-Security, X-Content-Type-Options, X-Frame-Options, and X-XSS-Protection, are present on the website, leaving it vulnerable to various types of attacks. Implementing these headers enhances the security of the website and protects against common web security threats.",
        'devExplanation' => "Check if no security headers are present on the website. If yes, consider implementing all required security headers for enhanced security.",
        'devExplanationDetail' => "To enhance security, check if no security headers, including Strict-Transport-Security, X-Content-Type-Options, X-Frame-Options, and X-XSS-Protection, are present on the website. Consider implementing these headers to protect against common web security threats and ensure a safer browsing experience for users.",
    ],

    // HEADERS_COOKIESECURITY_BOTH: Check if the site has secure and HttpOnly cookies
    'HEADERS_COOKIESECURITY_BOTH' => [
        'score' => 5,
        'maxScore' => 5,
        'basicExplanation' => "This site has secure and HttpOnly cookies.",
        'basicExplanationDetail' => "The website uses both secure and HttpOnly cookies, enhancing the security of user data by ensuring that cookies are transmitted over encrypted connections and are inaccessible to client-side scripts.",
        'premiumExplanation' => "This site has secure and HttpOnly cookies.",
        'premiumExplanationDetail' => "Secure and HttpOnly cookies are essential for protecting user data from unauthorized access and attacks. Secure cookies are transmitted over encrypted connections, while HttpOnly cookies are inaccessible to client-side scripts, reducing the risk of cross-site scripting (XSS) attacks.",
        'devExplanation' => "Check if the site has secure and HttpOnly cookies. If yes, it enhances security.",
        'devExplanationDetail' => "To enhance security, ensure that the site uses secure and HttpOnly cookies. Secure cookies should be transmitted over encrypted connections, while HttpOnly cookies should be inaccessible to client-side scripts, reducing the risk of cross-site scripting (XSS) attacks.",
    ],

    // HEADERS_COOKIESECURITY_SECURE: Check if the site has secure cookies but lacks HttpOnly attribute
    'HEADERS_COOKIESECURITY_SECURE' => [
        'score' => 3,
        'maxScore' => 5,
        'basicExplanation' => "This site has secure cookies but lacks HttpOnly attribute.",
        'basicExplanationDetail' => "The website uses secure cookies, which are transmitted over encrypted connections. However, these cookies lack the HttpOnly attribute, which may leave them vulnerable to cross-site scripting (XSS) attacks.",
        'premiumExplanation' => "This site has secure cookies but lacks HttpOnly attribute.",
        'premiumExplanationDetail' => "Secure cookies are transmitted over encrypted connections, enhancing the security of user data. However, these cookies lack the HttpOnly attribute, which may leave them vulnerable to cross-site scripting (XSS) attacks. Implementing the HttpOnly attribute ensures that cookies are inaccessible to client-side scripts, reducing the risk of XSS attacks.",
        'devExplanation' => "Check if the site has secure cookies but lacks the HttpOnly attribute. If yes, consider implementing the HttpOnly attribute for enhanced security.",
        'devExplanationDetail' => "To enhance security, ensure that secure cookies are transmitted over encrypted connections and implement the HttpOnly attribute to prevent client-side scripts from accessing them. This reduces the risk of cross-site scripting (XSS) attacks and protects user data from unauthorized access.",
    ],

    // HEADERS_COOKIESECURITY_NONE: Check if it could not verify if the site has secure and HttpOnly cookies
    'HEADERS_COOKIESECURITY_NONE' => [
        'score' => 0,
        'maxScore' => 5,
        'basicExplanation' => "Could not verify if this site has secure and HttpOnly cookies!",
        'basicExplanationDetail' => "The website's cookie security could not be verified, leaving uncertainty about whether it uses secure and HttpOnly cookies to protect user data.",
        'premiumExplanation' => "Could not verify if this site has secure and HttpOnly cookies!",
        'premiumExplanationDetail' => "The website's cookie security could not be verified, leaving uncertainty about whether it uses secure and HttpOnly cookies to protect user data. Implementing secure and HttpOnly cookies enhances the security of user data and reduces the risk of unauthorized access.",
        'devExplanation' => "Unable to verify if the site has secure and HttpOnly cookies. Consider checking the Set-Cookie headers in the response for secure and HttpOnly attributes.",
        'devExplanationDetail' => "To verify if the site has secure and HttpOnly cookies, check the Set-Cookie headers in the response for the presence of secure and HttpOnly attributes. Implementing secure and HttpOnly cookies enhances the security of user data and reduces the risk of unauthorized access.",
    ],

    // HEADERS_CORS_TRUE: Check if the site has CORS headers indicating cross-origin resource sharing
    'HEADERS_CORS_TRUE' => [
        'score' => 5,
        'maxScore' => 5,
        'basicExplanation' => "The response contains headers indicative of Cross-Origin Resource Sharing (CORS).",
        'basicExplanationDetail' => "The website's response contains headers indicating Cross-Origin Resource Sharing (CORS), allowing resources to be shared across different origins. This enhances the functionality and interactivity of web applications.",
        'premiumExplanation' => "The response contains headers indicative of Cross-Origin Resource Sharing (CORS).",
        'premiumExplanationDetail' => "The website's response contains headers indicative of Cross-Origin Resource Sharing (CORS), allowing resources to be shared across different origins. CORS is essential for enabling communication between web applications hosted on different domains, enhancing functionality and interactivity.",
        'devExplanation' => "Check if the site has CORS headers indicating cross-origin resource sharing. If yes, it enables communication between different origins.",
        'devExplanationDetail' => "To enable communication between different origins, ensure that the site has CORS headers indicating cross-origin resource sharing. CORS allows web applications hosted on different domains to interact with each other, facilitating the exchange of resources.",
    ],

    // HEADERS_CORS_FALSE: Check if it could not verify if the site has CORS headers
    'HEADERS_CORS_FALSE' => [
        'score' => 0,
        'maxScore' => 5,
        'basicExplanation' => "Could not verify if this site has CORS. No headers related to Cross-Origin Resource Sharing were detected.",
        'basicExplanationDetail' => "The presence of Cross-Origin Resource Sharing (CORS) headers could not be verified, leaving uncertainty about whether resources can be shared across different origins.",
        'premiumExplanation' => "Could not verify if this site has CORS. No headers related to Cross-Origin Resource Sharing were detected.",
        'premiumExplanationDetail' => "The presence of Cross-Origin Resource Sharing (CORS) headers could not be verified, leaving uncertainty about whether resources can be shared across different origins. CORS is essential for enabling communication between web applications hosted on different domains.",
        'devExplanation' => "Unable to verify if the site has CORS headers indicating cross-origin resource sharing. Consider checking the response headers for CORS-related information.",
        'devExplanationDetail' => "To verify if the site has CORS headers indicating cross-origin resource sharing, check the response headers for CORS-related information. CORS is essential for enabling communication between web applications hosted on different domains.",
    ],


    // HEADERS_CACHECONTROL_GOOD: Check if the site has good cache control
    'HEADERS_CACHECONTROL_GOOD' => [
        'score' => 5,
        'maxScore' => 5,
        'basicExplanation' => "This site has good cache control.",
        'basicExplanationDetail' => "The website has implemented good cache control measures, which help optimize performance and ensure that users receive fresh content.",
        'premiumExplanation' => "This site has good cache control.",
        'premiumExplanationDetail' => "Good cache control measures have been implemented on the website, optimizing performance and ensuring that users receive fresh content. Proper caching improves website speed and reduces server load.",
        'devExplanation' => "Check if the site has good cache control. If yes, it optimizes performance.",
        'devExplanationDetail' => "To optimize performance, ensure that the site has good cache control measures in place. Proper caching improves website speed, reduces server load, and enhances the overall user experience.",
    ],

    // HEADERS_CACHECONTROL_NONE: Check if it could not verify the site's cache control
    'HEADERS_CACHECONTROL_NONE' => [
        'score' => 0,
        'maxScore' => 5,
        'basicExplanation' => "Could not verify site's cache control!",
        'basicExplanationDetail' => "The website's cache control could not be verified, leaving uncertainty about its caching mechanisms.",
        'premiumExplanation' => "Could not verify site's cache control!",
        'premiumExplanationDetail' => "The website's cache control could not be verified, leaving uncertainty about its caching mechanisms. Implementing proper cache control measures improves website performance and user experience.",
        'devExplanation' => "Unable to verify if the site has good cache control. Consider checking the Cache-Control header in the response.",
        'devExplanationDetail' => "To verify if the site has good cache control, check the Cache-Control header in the response. Implementing proper cache control measures improves website performance and user experience.",
    ],


];

};

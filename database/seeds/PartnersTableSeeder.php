<?php

use Illuminate\Database\Seeder;

class PartnersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Partner::create([
            'img' => '/images/partners/acacia.png',
            'contact' => 'Greg Kentish',
            'email' => 'ceo@acaciaconnection.com',
            'phone' => '0417 023 287',
            'url' => 'www.acaciaconnection.com',
            'title' => 'Acacia Connection',
            'service' => 'Employee Assistance Program',
            'about' => 'Acacia Connection is one of Australia’s leading Employee Assistance providers.
                Acacia Connection are different to most EAP providers as they really care about people as individuals, not transactions.
                They work very closely with clients and provide them with the highest standard of service and support.
                Acacia Connection have a national network of over 650 psychologists and cover all regional and remote areas.',
            'benefit' => 'Members who refer work to Acacia Connection receive a 10% rebate on finalised sales.',
        ]);

        App\Partner::create([
            'img' => '/images/partners/blandslaw.jpg',
            'contact' => 'Andrew Bland',
            'email' => 'enquiries@blandslaw.com.au',
            'phone' => '(02) 9805 5600',
            'url' => 'www.blandslaw.com.au',
            'title' => 'BlandsLaw',
            'service' => 'Industrial Relations and Commercial law firm',
            'about' => 'BlandsLaw is a specialised and effective solution to assist you and your clients in their Industrial Relations needs.
                Complimenting and supporting the services you provide as an HR Coach, BlandsLaw provides the specialist advice to support your clients.',
            'benefit' => 'Members who refer work to BlandsLaw receive a 15% rebate on finalised sales.',
        ]);

        App\Partner::create([
            'img' => '/images/partners/foundu.png',
            'contact' => 'Matthew Horton',
            'email' => 'matthew@foundu.com.au',
            'phone' => '0408 748 521',
            'url' => 'www.foundu.com.au',
            'title' => 'foundU',
            'service' => 'Employee onboarding, rostering and payroll',
            'about' => 'foundU is an extremely innovative and nimble, Australian based employee rostering and payroll solution.
                Their system automates and streamlines the transactional side of onboarding new employees, budget reviewed rostering and single touch payroll.
                If your clients are spending more than an hour on rostering and paying employees a week, then this system will add value to their business.',
            'benefit' => 'Members who refer work to foundU receive a 5% rebate on finalised sales.',
        ]);

        App\Partner::create([
            'img' => '/images/partners/hrstaff.jpg',
            'contact' => 'Sam McCleary',
            'email' => 'admin@hrcoach.com.au',
            'phone' => '1300 550 674',
            'url' => 'www.hrstaffmanager.com.au',
            'title' => 'HR Staff Manager',
            'service' => 'Cloud based HR Information System.',
            'about' => 'HR Staff Manager is a web-based application that helps your clients manage the employee lifecycle process (with all processes underpinned by HR Coach methodology).
                HR Staff Manager helps streamline the transactional load of HR and upskill your clients employees to administer the system to allow you to focus on working tactically and strategically with your client.',
            'benefit' => 'Gold Network Members receive a 40% rebate on finalised sales; Bronze Network Members receive a 10% rebate on finalised sales.',
        ]);

        App\Partner::create([
            'img' => '/images/partners/hrsuccess.jpg',
            'contact' => 'Greg Mitchell',
            'email' => 'greg@hrsuccess.com.au',
            'phone' => '1300 783 211',
            'url' => 'www.hrsuccess.com.au',
            'title' => 'HR Success',
            'service' => 'Customised or pre-built eLearning solutions.',
            'about' => 'HR Success has an extensive library of eLearning solutions that have been created with HR Coach clients in mind.
                Clients also have the option of building their own eLearning solutions to ensure consistency across their business.',
            'benefit' => 'Receive discounted pricing on pre-built courses. Commission on custom builds by negotiation.',
        ]);

        App\Partner::create([
            'img' => '/images/partners/integro.jpg',
            'contact' => 'Keith Ayers',
            'email' => 'support@integro.com.au',
            'phone' => '1800 222 902',
            'url' => 'www.integro.com.au',
            'title' => 'Integro',
            'service' => 'Personality profiling, effective team building and conflict resolution tools.',
            'about' => 'Intégro are worldwide leading distributors of Everything DiSC® and The Five Behaviors of a Cohesive Team™
                who have been changing Australian workplaces with knowledge and experience in these solutions for over 30 years.',
            'benefit' => 'Receive discounted wholesale EPIC credit pricing and Everything DiSC accreditations.',
        ]);

        App\Partner::create([
            'img' => '/images/partners/mybusiness.jpg',
            'contact' => 'Sam McCleary',
            'email' => 'admin@hrcoach.com.au',
            'phone' => '1300 550 674',
            'url' => 'www.mybhc.com.au',
            'title' => 'My Business Health Check',
            'service' => 'Basic business diagnostic tool',
            'about' => 'My Business Health Check is a proprietary online tool of HR Coach Australasia.
                The Business health Check can be used to open the door to new or existing clients by asking them to complete a survey on their business, not just HR.
                Clients then receive a one page summary and the Coach receives a full report, complete with recommendations to toolkit and Business Partners solutions.',
            'benefit' => 'Gold Network Members receive a 50% discount on processing fees.',
        ]);

        App\Partner::create([
            'img' => '/images/partners/omnia.png',
            'contact' => 'Kate Brown',
            'email' => 'admin@omniaprofiling.com.au',
            'phone' => '0411 164 431',
            'url' => 'www.omniaprofiling.com.au',
            'title' => 'Omnia',
            'service' => 'Cognitive assessments and selection profiling',
            'about' => 'Omnia provide selection profiling and cognitive assessments to support hiring decisions.
                Take the guess work out of recruitment and rely on data to make the right choices for your clients.',
            'benefit' => 'Receive discounted wholesale pricing for all products',
        ]);

        App\Partner::create([
            'img' => '/images/partners/career.png',
            'contact' => 'Sam McCleary',
            'email' => 'admin@hrcoach.com.au',
            'phone' => '1300 550 674',
            'url' => 'www.careermonitor.com.au',
            'title' => 'Online Career Monitor',
            'service' => 'Key Employee Retention Interviews',
            'about' => 'Online interview tool to understand the motivators for key employees in a business with direct comparison between why they joined and why they stay.
                Use this tool to recognize key employees and ensure your client is doing everything they can to stop them moving on.',
            'benefit' => 'Proprietary tool of HR Coach Australasia with no competitors in the market.
                Gold Network Members receive 2 free Online Career Monitor’s each year.',
        ]);

        App\Partner::create([
            'img' => '/images/partners/onlinewhs.png',
            'contact' => 'Phil Bamford',
            'email' => 'phil@onlinecompliance.com.au',
            'phone' => '1800 020 389',
            'url' => 'www.onlinecompliancesystems.com.au',
            'title' => 'Online Compliance Systems',
            'service' => 'Cloud based safety management system',
            'about' => 'Online Compliance Systems (OCS) has been helping organisations manage their WHS requirements and create safer work environments for over eight years.
                OCS’s flagship product is Online WHS - a unique cloud-based system, which enables organisations to centralise
                and automate much of their WHS regulatory and operational processes, saving them time and money.',
            'benefit' => 'Members who refer work to Online Compliance receive between 7.5% and 12.5% (depending on level of involvement) rebate on finalised sales.',
        ]);

        App\Partner::create([
            'img' => '/images/partners/starworkplace.jpg',
            'contact' => 'Sam McCleary',
            'email' => 'admin@hrcoach.com.au',
            'phone' => '1300 550 674',
            'url' => 'www.starworkplace.com.au',
            'title' => 'STAR Workplace Program',
            'service' => 'Employer and employee engagement and alignment tool',
            'about' => 'STAR Workplace Program is the only engagement and alignment tool on the market that recognises the impact employers have on strategic
                alignment. Backed by over 10 years of research, results are benchmarked against over 700 Australasian businesses.',
            'benefit' => 'Proprietary tool of HR Coach Australasia, Gold Network Members receive 75% discount on processing fees;
                Bronze Network Members receive 50% discount on processing fees.',
        ]);

        App\Partner::create([
            'img' => '/images/partners/templetons.png',
            'contact' => 'Eric Chong',
            'email' => 'e.chong@templetons.com.au',
            'phone' => '0488 488 171',
            'url' => 'www.amp.com.au/ampadvice/templetons',
            'title' => 'Templetons Financial',
            'service' => 'Individual financial planning services and corporate superannuation specialists',
            'about' => 'Templetons Financial is a member of AMP Advice, providing coverage across Australia for HR Coaches and their clients. Templetons provide a cost effective solution
                for corporate superannuation funds as well as individual financial advice and outplacement sessions to help understand the financial implications of redundancy / termination.',
            'benefit' => 'Members who refer work to Online Compliance receive a 15% rebate on finalised sales.
                Network Members also receive discounts on their own individual financial planning.',
        ]);
    }
}

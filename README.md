## About Repository

I Created this task on PHP Programming Language and use one of the most demanding framework Laravel  

Pre-requisites
- [Php version should be >  7.3.
- [Composer version >= 2.*.
- [MySql not required for Future Api 5.7.
- [Apache localserver.


Steps to setup Laravel Project
- [git clone to this repo github(https://github.com/MuhammadFarzam/Contour-Tune/tree/master).
- [composer install.
- [cp .env.example .env modify for Database credential for future.
- [cmd ~ php artisan key:generate.
- [Run Apache Server then type "php artisan serve" generates local Url "http:localhost:8000/".


Demonstration for this task
- [Create One Controller UserDashboardController for web routing
- [Two Routes: 1st Route "http:localhost:8000/dashboard" for view page at first to user".
- [2nd Route "http:localhost:8000/filter-records" for fetching records on Dashboard through ajax request on apply Filter.
- [Create two Interfaces one is for JsonDatabase where connection is made and fetch initial query, second is UserDataRepository which is responsible for User related function.

Task includes
- [Fetch User record and their respective Statistics,
- [Sum Of Impressions, Conversions, Total Revenue, and Conversions per day of User.
- [HightCharts library is used for Charts [HighCharts](https://www.highcharts.com/).
- [Graph to show conversions per day,
- [Filter records by name or impression or conversion or revenue at a time.
- [Responsive design for all respective screen.
- [Extendable to Api for future use

Future Improvements
- [Migrate data in to MysqlDatabase for better query result and response.
- [Develop Api's
- [Search should be used for Selected Fields
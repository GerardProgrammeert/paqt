## Description
This application is developed for a job interview at Paqt. 

It allows call centers to book rides for residents of Utrecht when the WMO decision permits it. 
Taxi firms can view rides for their own area. 

## Install
To install this Laravel(v10) app, please follow these steps: 

From the root of the project run 

````
# php artisan sail install
# ./vendor/bin/sail up -d
````
This will create an image and a container with dependencies to run this Laravel project.

After Laravel is installed, run the installation script from the root of the project inside the container.
````
sh install.sh
````
This script will run the migration files and seeders

The following seeders will be run:
#### DecisionSeeder 
This seeder will create residents and a decisions. 
These residents are needed to book rides. The decision contains balance for the budget.
#### RideSeeder
This seeder will create rides with a resident. 
#### AccountSeeder
This will create accounts. These are needed to view booked rides.

## Technical explanation
This section will explain how the entities are related to each other and how the decision are reset. 

#### Resident
Resident has a field area.
It has a one on one relationship with decision and a one on many relationship with rides.  

#### Decision
Decision has a one on one relation with resident. It contains a balance for the budget. In case a ride the distance will be subtracted from the balance.
In case the decision is inactive or the distance of the ride exceeds the balence the ride will not be booked.  

#### Accounts
An account holds the area where it is responsible for executing the rides. 
This is an integer value. This value is used to get the rides book by the residents.

#### Rides
When a ride is book it will update the balance on the decision. 

#### Scheduled Decision Job
This job is run every day at midnight. It will fetch every active decision with a passed prolongation date.
It will reset the balance and set the prolongation date for next year.

## How to use

To know how to use this api please read the [documentation on the endpoints](https://gerardprogrammeert.stoplight.io/docs/rides-booking-wmo/8b1jchhl3opvr-booking-rides-wmo)
Here you can also find some curl examples on how to interact with the api.  

#### Book a ride example
````
curl --location --request POST 'http://localhost/api/rides/' \
--header 'Content-Type: application/json' \
--header 'Accept: application/json' \
--data-raw '{
    "resident_id":"1",
    "pickup_moment":"20213-12-30 13:00",
    "from":"Barendrecht, Stationsweg 14", 
    "to":"Rotterdam, Blaak 10", 
    "distance":"150000"  
}'
````

#### Get rides overview example
````
curl --location --request GET 'http://localhost/api/residents'
````

#### Get all residents example
````
curl --location --request GET 'http://localhost/api/residents'
````

## Resources
- [API documentation](https://gerardprogrammeert.stoplight.io/docs/rides-booking-wmo/8b1jchhl3opvr-booking-rides-wmo)


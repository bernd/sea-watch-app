Databases
=========

+--------------------------+
| Tables                   |
+--------------------------+
| emergency_case_locations |
| emergency_case_messages  |
| emergency_cases          |
| involved_users           |
| operation_areas          |
+--------------------------+


emergency_cases  
---------------

+-------------------------+------------------+------+-----+---------------------+----------------+
| Field                   | Type             | Null | Key | Default             | Extra          |
+-------------------------+------------------+------+-----+---------------------+----------------+
| id                      | int(10) unsigned | NO   | PRI | NULL                | auto_increment |
| boat_status             | varchar(255)     | NO   |     | NULL                |                |
| boat_condition          | varchar(255)     | NO   |     | NULL                |                |
| boat_type               | varchar(255)     | NO   |     | NULL                |                |
| other_involved          | varchar(255)     | NO   |     | NULL                |                |
| engine_working          | varchar(255)     | NO   |     | NULL                |                |
| passenger_count         | int(11)          | NO   |     | NULL                |                |
| additional_informations | text             | NO   |     | NULL                |                |
| spotting_distance       | double(8,2)      | NO   |     | NULL                |                |
| spotting_direction      | int(11)          | NO   |     | NULL                |                |
| picture                 | varchar(255)     | NO   |     | NULL                |                |
| session_token           | varchar(255)     | NO   |     | NULL                |                |
| operation_area          | int(11)          | NO   |     | NULL                |                |
| created_at              | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
| updated_at              | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
| source_type             | varchar(255)     | NO   |     | NULL                |                |
+-------------------------+------------------+------+-----+---------------------+----------------+



emergency_case_locations
------------------------

+-------------------+------------------+------+-----+---------------------+----------------+
| Field             | Type             | Null | Key | Default             | Extra          |
+-------------------+------------------+------+-----+---------------------+----------------+
| id                | int(10) unsigned | NO   | PRI | NULL                | auto_increment |
| emergency_case_id | int(11)          | NO   |     | NULL                |                |
| lat               | double(10,7)     | NO   |     | NULL                |                |
| lon               | double(10,7)     | NO   |     | NULL                |                |
| accuracy          | int(11)          | NO   |     | NULL                |                |
| heading           | int(11)          | NO   |     | NULL                |                |
| created_at        | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
| updated_at        | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
| connection_type   | varchar(60)      | YES  |     | NULL                |                |
| message           | text             | YES  |     | NULL                |                |
+-------------------+------------------+------+-----+---------------------+----------------+



emergency_case_locations
------------------------

+----------------------------+------------------+------+-----+---------------------+----------------+
| Field                      | Type             | Null | Key | Default             | Extra          |
+----------------------------+------------------+------+-----+---------------------+----------------+
| id                         | int(10) unsigned | NO   | PRI | NULL                | auto_increment |
| emergency_case_id          | int(11)          | NO   |     | NULL                |                |
| emergency_case_location_id | int(11)          | NO   |     | NULL                |                |
| receiver_type              | varchar(255)     | NO   |     | NULL                |                |
| receiver_id                | varchar(255)     | NO   |     | NULL                |                |
| sender_type                | varchar(255)     | NO   |     | NULL                |                |
| sender_id                  | varchar(255)     | NO   |     | NULL                |                |
| created_at                 | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
| updated_at                 | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
| seen                       | int(11)          | NO   |     | NULL                |                |
| message                    | mediumtext       | YES  |     | NULL                |                |
+----------------------------+------------------+------+-----+---------------------+----------------+

operation_areas
---------------
+---------------------+------------------+------+-----+---------------------+----------------+
| Field               | Type             | Null | Key | Default             | Extra          |
+---------------------+------------------+------+-----+---------------------+----------------+
| id                  | int(10) unsigned | NO   | PRI | NULL                | auto_increment |
| title               | varchar(255)     | NO   |     | NULL                |                |
| polygon_coordinates | text             | NO   |     | NULL                |                |
| user_id             | int(11)          | NO   |     | NULL                |                |
| active              | int(11)          | NO   |     | NULL                |                |
| created_at          | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
| updated_at          | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
+---------------------+------------------+------+-----+---------------------+----------------+

involved_users
--------------

+-------------------+-----------+------+-----+---------------------+-------+
| Field             | Type      | Null | Key | Default             | Extra |
+-------------------+-----------+------+-----+---------------------+-------+
| user_id           | int(11)   | NO   |     | NULL                |       |
| case_id           | int(11)   | NO   |     | NULL                |       |
| last_message_seen | int(11)   | NO   |     | NULL                |       |
| created_at        | timestamp | NO   |     | 0000-00-00 00:00:00 |       |
| updated_at        | timestamp | NO   |     | 0000-00-00 00:00:00 |       |
+-------------------+-----------+------+-----+---------------------+-------+












RESET ALL case databases:

    TRUNCATE TABLE emergency_case_locations;
    TRUNCATE TABLE emergency_case_messages;
    TRUNCATE TABLE emergency_cases;
    TRUNCATE TABLE involved_users;        
    TRUNCATE TABLE operation_areas;         

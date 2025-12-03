### Nicole

I unfotunately ran into a LOT of trouble getting my database to work locally. I think something in my XAMPP config file is messed up, but I could not for the life of me get any user on phpmyadmin to be able to access the database I created, whether the root user or creating a new user and granting the necessary priveledges, the itws2110-fall2025-spauln-quiz2 database I created could not be fetched from (for new users i created it said incorrect password even when it was exactly identical and the entire database was not visible to the root user when I had it print all available databses). Due to this, I was unable to truly test any of the code I wrote after break (register.php and some of index.php), I'm so sorry if that results in a lot of the code being a bit confusing/error prone.

!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
NOTE: Due to this, I was unable to complete 2.6 or get any screenshots of my site working since I could not run my index.php T-T
Sorry for the inconvenience!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

----- Here is my answers to the questions -----

3.1. Any design decisions that you took in completing this quiz.

I decided to try to be extra safe since I couldn't test my code, this included trimming entered characters by the user into the html text fields, using rollback when catching errors in inserting into tables to stop bad inserts from effecting the DB. In addition, when setting up my database I made it so the project membership table detailed cascading deletes so it would be easier to maintain if I messed up while adding the members, but that didn't really end up mattering.

3.2. Describe how you would handle a situation where a user came to the site
for the very first time and no database existed (Think install)

Likely a good thing to do might be to have some sort of database setup file that could be run that creates the necessary DB and tables (only in the case that none existed). This could be done by having a php file with these commands (setupDB file commands) that are executed in the try catch block of dbConnect.php file if it's found that the database connection succeeds but a quick check to the contents of the database shows it's empty, then these commands could be run once to setup.

3.3. How could you add functionality to prevent duplicate entries for the same
project?

I had actually misread and thought this was required; All I did was make sure only unique project ideas could be created from index.php, ones that are duplicate resulted in an error message.

3.4. Suppose you want to include functionality to let people vote on the final
in-class project presentations.

3.4.1. What additional table(s) will you include to support this?

I would make a table projectVotes table to track the votes made.


3.4.2. How will you structure the data in these table(s)?

 I would have columns:
    - voteId to make track individual vodes
    - projectId corresponding to the project voted for
    - userId that is the user who made the vote

3.4.3. How could you add functionality to prevent users from submitting a vote to their own project?

Similar to stopping duplicate projects, supoose that the functionality for voting is found on a 3rd option on index "Vote for Projects", then there could just be a check that checks the projectId of the proposed vote (after the transaction is started so a rollback could undo it) and only let the vote be inserted to the projectVotes table if there is no user found matching the current session userId.


Firstly, because of the limited time, I have just implemented the program with a simple solution.
It does not cover all cases that can make issues. I will point out later.
The program is to show my code implementation with some tests.
I hope you can understand my ideas. If you have some questions, we can discuss more details if we have an interview.


# Features
1. The program will take input from url parameter, such as http://localhost:80?url=https://en.wikipedia.org/wiki/Women%27s_high_jump_world_record_progression
2. The program will scan the page for a table with a numeric column.
3. The numeric values is to plot a graph.
4. For command line environment, you can see the graph image in `app/public/images/graph.png`.
5. For web environment, you can see graph on web page.
6. The program execution is on Docker environment with my own built docker image.

#Tests
1. Throw error if the given url is inaccessible.
2. Throw error if there is no table inside given page url.
3. Throw error if there is no matching condition column (numeric in this case). It also checks all tables, not only one.
4. Successfully getting numeric column.


# Execution
1. Install Docker https://docs.docker.com/engine/install/ubuntu/
2. Pull docker image: `$ docker pull lehongduc87/php-nginx`
3. Download and extract the program zip file. Source code is located in app directory.
4. Brown the terminal to program root and run: `docker run -v ./app:/www lehongduc87/php-nginx`
5. Access to docker container: `docker exec -it container_name bash`
6. Go to `www` directory and run command `composer install`
7. To run program: `php public/index.php https://en.wikipedia.org/wiki/Women%27s_high_jump_world_record_progression`
8. To access web page `http://localhost:80?url=https://en.wikipedia.org/wiki/Women%27s_high_jump_world_record_progression`


# Solutions
### Report
There are many kind of graphs which requiring different dataset, such as line, bar, bubble charts...
A simple graph should have at least set of labels and values, not only values to display.
Because the practise test requests to display numeric to graph, I assume the labels are empty.
However, the code is able to get the label column because of list of stored column values. It needs a little change.
Because the label can be any column, then the program must have an interface to select columns (label and maybe value) to display.
### Numeric column
Normally, I just check the real numeric value, not mixed numeric and alphabet characters.
In this program, I had hardcode pattern which check numeric like "12.1 m (...)" and get value 12.1 to display.
I also checked all row values to make sure no mixes of different row value types.
Because of limited time, this program is not validating all value cases (date, time) which may has same issue.
### Table style
This program is working with happy case that table have only one table header.
Not handled for special table headers (mixed horizontal and vertical headers) .
### Testing
I had also not implemented all cases of this program. Can see later.


#### If you have any more questions, please feel free to contact me.

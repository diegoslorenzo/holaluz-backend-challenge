# Suspicious Readings Detector

## About

This project allows to detect suspicious readings in energy consumption data from different sources (CSV, XML...).
It has been designed with hexagonal architecture to decouple the business logic from the inputs and outputs, facilitating extension and maintenance.

## 🛠️ Technology

- **Programming Language**: PHP 8.4
- **Framework**: Symfony 6.4
- **Containers**: Docker, Docker Compose
- **Testing**: PHPUnit
- **Version Control**: Git, GitHub

## 🏗️ Architecture and scope

The project follows Hexagonal Architecture, separating business logic from infrastructure adapters to improve decoupling and extensibility.

### 💡 Benefits:

- Allows you to easily add new data sources (DB, FTP, API, etc.).
- Facilitates unit testing and system evolution without impacting business logic.
- Maintains a clear separation between domain, application, and infrastructure.
- Easier to expose functionality via CLI or API.

### Main components

**📂 Domain**

**Reading.php**: 

Represents an energy consumption reading with this attributes:
- `client`: Client ID.
- `period`: Month of the reading.
- `value`: Value of the reading.
- `median`: Median calculated for the client

**`isSuspicious()`** method:

- Allows determining whether a reading is suspicious based on the difference from the median.

**SuspiciousReadingsDetector.php**

Applies suspicious readings detection logic, identifying values ​​outside established thresholds (readings that are either higher or lower than the annual median ± 50%).

**📂 Application**

**ReaderService.php:**

Is the orchestrator of the data reading and analysis process. Its main function is to coordinate the execution of the different readers available in the infrastructure, process the data obtained and apply the suspicious reading detection logic defined in the domain.

- It connects to the infrastructure readers through ReaderFactory, allowing the processing of different file formats without coupling to a single input type.
- It applies the detection logic through SuspiciousReadingsDetector, a domain component that evaluates whether a reading deviates significantly from the historical median.
- It returns the results in a structured format that allows its use in other layers of the system, such as report generation or console visualization.

**📂 Infrastructure**

- Readers: CSVReader.php, XMLReader.php, TXTReader.php.

  - Although the requirement was to implement CSVReader and XMLReader, TXTReader has been added to demonstrate the ease with which new input formats can be incorporated thanks to the decoupling of the architecture.

- CLI Command: DetectSuspiciousReadingsCommand.php.

  - Allows you to run suspicious read detection from the command line.


### ➕ Additional scope

**API HTTP: DetectSuspiciousReadingsController.php.**

Added the option to receive data via API (POST request) with a JSON body, showing that the application can be extended to process data from other sources, such as an API instead of a file by running a CLI command


**🏭 Factory for Additional Decoupling**

Using the inversion principle and reducing coupling with Symfony, a reader factory (ReaderFactory.php) has been developed that allows readers to be instantiated without depending on Symfony's services.yaml. This functionality is currently commented out* and not used in the default version, but it demonstrates how the code could be further decoupled if needed.

**Uncommenting it would require adjusting some tests*


## 🐳 Deployment

### Prerequisites
- Docker installed.
- Git for version control.

### Quickstart

1. Clone the Repository

```git clone git@github.com:diegoslorenzo/holaluz-backend-challenge.git```

or:

```git clone https://github.com/diegoslorenzo/holaluz-backend-challenge.git```

or download it from https://github.com/diegoslorenzo/holaluz-backend-challenge

2. Go to the project

```cd holaluz-backend-challenge```

3. Build and Deploy Docker Containers

```docker-compose up --build -d```

4. Run from CLI

```docker exec -it suspicious-reading-detector php bin/console app:detect-suspicious-readings readings/2016-readings.csv```

```docker exec -it suspicious-reading-detector php bin/console app:detect-suspicious-readings readings/2016-readings.xml```

```docker exec -it suspicious-reading-detector php bin/console app:detect-suspicious-readings readings/2016-readings.txt```

```docker exec -it suspicious-reading-detector php bin/console app:detect-suspicious-readings readings/2016-readings.json```

5. Send an API request

``` 
curl -X POST http://localhost:8000/detect-suspicious-readings \
     -H "Content-Type: application/json" \
     -d '{
          "readings": [
            { "clientID": "A", "period": "2016-01", "reading": 300 },
            { "clientID": "A", "period": "2016-02", "reading": 300 },
            { "clientID": "A", "period": "2016-03", "reading": 200 },
            { "clientID": "A", "period": "2016-04", "reading": 500 },
            { "clientID": "A", "period": "2016-05", "reading": 300 },
            { "clientID": "A", "period": "2016-06", "reading": 290 },
            { "clientID": "A", "period": "2016-07", "reading": 390 },
            { "clientID": "A", "period": "2016-08", "reading": 900 },
            { "clientID": "A", "period": "2016-09", "reading": 200 },
            { "clientID": "A", "period": "2016-10", "reading": 1200 },
            { "clientID": "A", "period": "2016-11", "reading": 100 },
            { "clientID": "A", "period": "2016-12", "reading": 400 }
          ]
        }'
```


JSON input example:
```
{
  "readings": [
    { "clientID": "A", "period": "2016-01", "reading": 300 },
    { "clientID": "A", "period": "2016-02", "reading": 300 },
    { "clientID": "A", "period": "2016-03", "reading": 200 },
    { "clientID": "A", "period": "2016-04", "reading": 500 },
    { "clientID": "A", "period": "2016-05", "reading": 300 },
    { "clientID": "A", "period": "2016-06", "reading": 290 },
    { "clientID": "A", "period": "2016-07", "reading": 390 },
    { "clientID": "A", "period": "2016-08", "reading": 900 },
    { "clientID": "A", "period": "2016-09", "reading": 200 },
    { "clientID": "A", "period": "2016-10", "reading": 1200 },
    { "clientID": "A", "period": "2016-11", "reading": 100 },
    { "clientID": "A", "period": "2016-12", "reading": 400 }
  ]
}
```

## 🧪 Testing

Running tests:

```docker exec -it suspicious-reading-detector php bin/phpunit```

or for natural format:

```docker exec -it suspicious-reading-detector php bin/phpunit --testdox```


## 🔄 Future implementations

- ✅ Add a proper request validation
- ✅ Improve exception handling
- ✅ Handle large amount of data,
- ✅ Support for database.
- ✅ ...
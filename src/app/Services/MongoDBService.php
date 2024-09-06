<?php

namespace App\Services;

use MongoDB\Client;
use MongoDB\Collection;

class MongoDBService
{
    protected $client;
    protected $database;

    public function __construct()
    {
        $this->client = new Client(env('MONGO_DB_URI', 'mongodb://root:example@host.docker.internal:27017'));
        $this->database = $this->client->selectDatabase(env('MONGODB_DATABASE', 'local'));
    }

    public function getCollection(string $collectionName): Collection
    {
        return $this->database->selectCollection($collectionName);
    }

    public function insertDocument(string $collectionName, array $document)
    {
        $collection = $this->getCollection($collectionName);
        $result = $collection->insertOne($document);
        return $result->getInsertedId();
    }

    public function findDocuments(string $collectionName, array $filter = [], array $options = [])
    {
        $collection = $this->getCollection($collectionName);
        return $collection->find($filter, $options);
    }

    public function updateDocument(string $collectionName, array $filter, array $update)
    {
        $collection = $this->getCollection($collectionName);
        $result = $collection->updateOne($filter, ['$set' => $update]);
        return $result->getModifiedCount();
    }

    public function deleteDocument(string $collectionName, array $filter)
    {
        $collection = $this->getCollection($collectionName);
        $result = $collection->deleteOne($filter);
        return $result->getDeletedCount();
    }
}

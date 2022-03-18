<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository;

use JoJoBizzareCoders\DigitalJournal\Entity\ParentRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\ParentUserClass;
use JoJoBizzareCoders\DigitalJournal\Exception\InvalidDataStructureException;
use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Auth\UserDataProviderInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Auth\UserDataStorageInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Db\ConnectionInterface;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Address;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Fio;

/**
 * Репозиторий для работы с родителями черед бд
 *
 *
 */
final class ParentDbRepository implements ParentRepositoryInterface, UserDataStorageInterface
{
    /**
     * @var ConnectionInterface
     */
    private ConnectionInterface $connection;

    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @inheritDoc
     */
    public function findBy(array $criteria): array
    {
        $sql = <<<EOF
SELECT 
       u.id, 
       u.surname, 
       u.name, 
       u.patronymic, 
       u.date_of_birth,
       u.phone, 
       u.street,
       u.home, 
       u.apartment, 
       p.place_of_work,
       p.email, 
       u.login, 
       u.password 
FROM users as u
join parents p on u.id = p.id
EOF;
        $whereParts = [];
        foreach ($criteria as $fieldName => $fieldValue) {
            $whereParts[] = "$fieldName = '$fieldValue'";
        }
        if (count($whereParts) > 0) {
            $sql .= ' where ' . implode(' and ', $whereParts);
        }
        $dataFromDb = $this->connection->query($sql)->fetchAll();
        $foundEntities = [];
        foreach ($dataFromDb as $item) {
            $arrayFio = [];
            $arrayFio[0]['surname'] = $item['surname'];
            $arrayFio[0]['name'] = $item['name'];
            $arrayFio[0]['patronymic'] = $item['patronymic'];
            $arrayAddress = [];
            $arrayAddress[0]['street'] = $item['street'];
            $arrayAddress[0]['home'] = $item['home'];
            $arrayAddress[0]['apartment'] = $item['apartment'];
            $arrayInfoForParent = [];
            $arrayInfoForParent['id'] = $item['id'];
            $arrayInfoForParent['fio'] = $arrayFio;
            $arrayInfoForParent['dateOfBirth'] = $item['date_of_birth'];
            $arrayInfoForParent['phone'] = $item['phone'];
            $arrayInfoForParent['address'] = $arrayAddress;
            $arrayInfoForParent['placeOfWork'] = $item['place_of_work'];
            $arrayInfoForParent['email'] = $item['email'];
            $arrayInfoForParent['login'] = $item['login'];
            $arrayInfoForParent['password'] = $item['password'];
            $foundEntities[] = $this->parentFactory($arrayInfoForParent);
        }
        return $foundEntities;
    }

    /**
     * Метод отвечающий за создание объекта родителя студента
     *
     * @param $parent - данные найденных родителей
     * @return ParentUserClass - объект класса родителей
     */
    private function parentFactory($parent): ParentUserClass
    {
        $parent['fio'] = $this->createFioArray($parent);
        $parent['address'] = $this->createAddressArray($parent);
        return ParentUserClass::createFromArray($parent);
    }

    /**
     * Метод создания массива объектов ФИО родителя
     *
     * @param $parent
     * @return array
     */
    private function createFioArray($parent): array
    {
        if (false === array_key_exists('fio', $parent)) {
            throw new InvalidDataStructureException('Нет данных о полном имени родителя');
        }
        if (false === is_array($parent['fio'])) {
            throw new InvalidDataStructureException(
                'Данные о полном имени родителя имеют не верный формат'
            );
        }
        $fio = [];
        foreach ($parent['fio'] as $userData) {
            $fio[] = $this->createFio($userData);
        }
        return $fio;
    }

    /**
     * @param $parent
     * @return array
     */
    private function createAddressArray($parent): array
    {
        if (false === array_key_exists('address', $parent)) {
            throw new InvalidDataStructureException('Нет данных о адресе родителя');
        }
        if (false === is_array($parent['address'])) {
            throw new InvalidDataStructureException(
                'Данные о адресе родителя имеют не верный формат'
            );
        }
        $address = [];
        foreach ($parent['address'] as $userData) {
            $address[] = $this->createAddress($userData);
        }
        return $address;
    }

    /**
     * Создание полного имени родителя ученика
     *
     * @param $userData - данные о пользователе, родитель ученика
     * @return Fio - объект полного имени
     */
    private function createFio($userData): Fio
    {
        if (false === is_array($userData)) {
            throw new InvalidDataStructureException(
                'Данные о полном имени родителя имеют неверный формат'
            );
        }
        if (false === array_key_exists('surname', $userData)) {
            throw new InvalidDataStructureException(
                'Отсутствует фамилия родителя'
            );
        }
        if (false === is_string($userData['surname'])) {
            throw new InvalidDataStructureException(
                'Фамилия родителя имеет не верный формат'
            );
        }
        if (false === array_key_exists('name', $userData)) {
            throw new InvalidDataStructureException(
                'Отсутствует имя родителя'
            );
        }
        if (false === is_string($userData['name'])) {
            throw new InvalidDataStructureException(
                'Имя родителя имеет не верный формат'
            );
        }
        if (false === array_key_exists('patronymic', $userData)) {
            throw new InvalidDataStructureException(
                'Отсутствует отчество родителя'
            );
        }
        if (false === is_string($userData['patronymic'])) {
            throw new InvalidDataStructureException(
                'Отчество родителя имеет не верный формат'
            );
        }
        return new Fio(
            $userData['surname'],
            $userData['name'],
            $userData['patronymic']
        );
    }

    /**
     * Реализация логики метода создания объекта адреса проживания родителя студента
     *
     * @param $userData - информация об адресе проживания родителя студента
     * @return Address - объект с Адисом проживания родителя студента
     */
    private function createAddress($userData): Address
    {
        if (false === is_array($userData)) {
            throw new InvalidDataStructureException(
                'Данные о адресе проживания родителя имеют не верный формат'
            );
        }
        if (false === array_key_exists('street', $userData)) {
            throw new InvalidDataStructureException(
                'Отсутствует название улицы адреса проживания родителя'
            );
        }
        if (false === is_string($userData['street'])) {
            throw new InvalidDataStructureException(
                'Название улицы адреса проживания родителя имеет не верный формат'
            );
        }
        if (false === array_key_exists('home', $userData)) {
            throw new InvalidDataStructureException(
                'Отсутствует номер дома адреса проживания родителя'
            );
        }
        if (false === is_string($userData['home'])) {
            throw new InvalidDataStructureException(
                'Номер дома адреса проживания родителя имеет не верный формат'
            );
        }
        if (false === array_key_exists('apartment', $userData)) {
            throw new InvalidDataStructureException(
                'Отсутствует номер квартиры адреса проживания родителя'
            );
        }
        if (false === is_string($userData['apartment'])) {
            throw new InvalidDataStructureException(
                'Номер квартиры адреса проживания родителя имеет не верный формат'
            );
        }
        return new Address(
            $userData['street'],
            $userData['home'],
            $userData['apartment']
        );
    }

    public function findUserByLogin(string $login): ?UserDataProviderInterface
    {
        $entities = $this->findBy(['login' => $login]);
        $countEntities = count($entities);

        if ($countEntities > 1) {
            throw new RuntimeException('Найдены пользователи с дублирующимися логинами');
        }

        return 0 === $countEntities ? null : current($entities);

    }
}

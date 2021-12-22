<?php

/**
 * Created by Wagaana Alex
 */
class EMAIL
{
    public static function create_tables()
    {
        $query = "create table if not exists sentMessages(
		messageID int auto_increment not null,
		sender char(100) not null,
		senderID char(100),
		phone char(100) not null,
		message char(100),
        region char(100),
        response char(100),
        templateId char(100),
		time timestamp NOT NULL default CURRENT_TIMESTAMP,
		primary key(messageID)
		)";
        $result = DB::query($query);
        if (!$result) {
            return false;
        }

        $query = "create table if not exists places(
		placeId int auto_increment not null,
        label char(100),
        district char(100),
        subCounty char(100),
        county char(100),
        village char(100),
        region char(100),
        country char(100),
		timeAdded timestamp NOT NULL default CURRENT_TIMESTAMP,
		primary key(placeId)
		)";
        $result = DB::query($query);
        if (!$result) {
            return false;
        }

        $query = "create table if not exists users(
		UserId int auto_increment not null,
		phone char(100) not null,
        placeId char(100),
        latitude char(100),
        longitude char(100),
        firstName char(100),
        lastName char(100),
        lavel char(100),
        dob char(100),
        gender char(100),
		time timestamp NOT NULL default CURRENT_TIMESTAMP,
		primary key(UserId)
		)";
        $result = DB::query($query);
        if (!$result) {
            return false;
        }

        $query = "create table if not exists templates(
		templateId int auto_increment not null,
        targetGender char(100),
        targetAgeRange char(100),
        targetLocation char(100),
        message char(100),
        senderId char(100),
        numberOfSMSmsg char(100),
        periodicTime char(100),
        periodic char(100),
		time timestamp NOT NULL default CURRENT_TIMESTAMP,
		primary key(templateId)
		)";
        $result = DB::query($query);
        if (!$result) {
            return false;
        }

        $query = "create table if not exists messageCampaignRegions(
		id int auto_increment not null,
        templateID char(100),
        regionID char(100),
		time timestamp NOT NULL default CURRENT_TIMESTAMP,
		primary key(id)
		)";
        $result = DB::query($query);
        if (!$result) {
            return false;
        }

        $query = "create table if not exists companies(
		id int auto_increment not null,
        clientID char(100) not null,
        countryCode char(100) not null,
        countryID char(100) not null,
        phoneNumber char(100) not null,
        shortNumber char(100) not null,
        companyAvator TEXT,
        companyDescription TEXT,
        companyEmail char(100),
        companyName char(100),
        companyPhone char(100) not null,
        companyWebsite char(100),
        walletBalance char(100),
        privateKey char(100),
        publicKey char(100),
		timeAdded timestamp NOT NULL default CURRENT_TIMESTAMP,
		primary key(id)
		)";
        $result = DB::query($query);
        if (!$result) {
            return false;
        }

        $query = "create table if not exists sentEmails(
		id int auto_increment not null,
        email char(100) not null,
        name char(100) not null,
        subject char(100) not null,
        body TEXT,
        AltBody TEXT,
		timeSent timestamp NOT NULL default CURRENT_TIMESTAMP,
		primary key(id)
		)";
        $result = DB::query($query);
        if (!$result) {
            return false;
        }

        $query = "create table if not exists failedEmails(
		id int auto_increment not null,
        email char(100) not null,
        name char(100) not null,
        subject char(100) not null,
        body TEXT,
        AltBody TEXT,
		timeSent timestamp NOT NULL default CURRENT_TIMESTAMP,
		primary key(id)
		)";
        $result = DB::query($query);
        if (!$result) {
            return false;
        }

        $query = "create table if not exists messagesFromWebsite(
		id int auto_increment not null,
        email char(100) not null,
        name char(100) not null,
        body TEXT,
		timeSent timestamp NOT NULL default CURRENT_TIMESTAMP,
		primary key(id)
		)";
        $result = DB::query($query);
        if (!$result) {
            return false;
        }
    }

    public static function SaveMessagesFromWebsite($email, $name, $body)
    {
        $result = DB::query("INSERT INTO messagesFromWebsite(email, name, body) 
        VALUES('" . DB::esc($email) . "', '" . DB::esc($name) . "', '" . DB::esc($body) . "')");
        if (!$result) {
            return false;
        } else {
            return true;
        }
    }

    public static function SendEmail($toAddress, $reciepientName, $subject, $body, $AltBody)
    {
        $phpMailer = new PHPMailer;

        // $phpMailer->SMTPDebug = 4;

        $phpMailer->isSMTP();
        $phpMailer->Host = 'smtp.gmail.com';
        $phpMailer->SMTPAuth = true;
        $phpMailer->Username = 'dev@yellowbird.mobi';
        $phpMailer->Password = 'yellowbird@2019++';
        $phpMailer->SMTPSecure = 'tls';
        $phpMailer->Port = 587;

        $phpMailer->setFrom("contact@yellowbird.mobi", 'YellowBIRD');
        $phpMailer->addAddress($toAddress, $reciepientName);
        $phpMailer->isHTML(true);

        $phpMailer->Subject = $subject;
        $phpMailer->Body    = $body;
        $phpMailer->AltBody = $AltBody;

        if (!$phpMailer->send()) {
            self::SaveEmailFailed($toAddress, $reciepientName, $subject, $body, $AltBody);
            return 'Message could not be sent.';
        } else {
            self::SaveEmailSent($toAddress, $reciepientName, $subject, $body, $AltBody);
            return 'Message has been sent';
        }
    }

    public static function SaveEmailFailed($email, $name, $subject, $body, $AltBody)
    {
        $result = DB::query("INSERT INTO failedEmails(email, name, subject, body, AltBody) 
        VALUES('" . DB::esc($email) . "', '" . DB::esc($name) . "', '" . DB::esc($subject) . "', '" . DB::esc($body) . "', '" . DB::esc($AltBody) . "')");
        if (!$result) {
            return false;
        } else {
            return true;
        }
    }

    public static function SaveEmailSent($email, $name, $subject, $body, $AltBody)
    {
        $result = DB::query(
            "INSERT INTO sentEmails(email, name, subject, body, AltBody) 
        VALUES('" . DB::esc($email) . "', '" . DB::esc($name) . "', '" . DB::esc($subject) . "', '" . DB::esc($body) . "', '" . DB::esc($AltBody) . "')"
        );
        if (!$result) {
            return false;
        } else {
            return true;
        }
    }

    public static function SendMultipleEmails($AltBody, $body, $subject, $objectsArray)
    {
        $objectsArrayList = array();
        $objectsArrayList = json_decode($objectsArray);

        foreach ($objectsArrayList as $object) {
            $email = $object->email;
            $firstName = $object->firstName;
            $lastName = $object->lastName;

            $reciepientName = $firstName + " " + $lastName;
            self::SaveEmailSent($email, $reciepientName, $subject, $body, $AltBody);
            self::SendEmail(
                $email,
                $reciepientName,
                $subject,
                $body,
                $AltBody
            );
        }
        return $objectsArrayList;
    }
}

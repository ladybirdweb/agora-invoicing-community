<?php

namespace Tests\Unit;

use Tests\DBTestCase;

class LocalizedLicenseControllerTest extends DBTestCase
{
    /** @group LocalizedLicense */
    public function test_chooseLicenseMode_fileChosen_returnStatusChangeSuccessfully()
    {
        $this->withoutMiddleware();
        \App\Model\Order\Order::create(['number' => 192020, 'order_status' => 'executed', 'product' => 28]);
        $data = [
            'choose' => 1,
            'orderNo' => 192020,
        ];
        $response = $this->json('POST', url('choose'), $data);
        $response->assertStatus(200);
        $response->assertJson(['success' => 'Status change successfully.']);
    }

    /** @group LocalizedLicense */
    public function test_storeFile_enterDomainToGenerateLicenseFile_returnstatus302()
    {
        $this->withoutMiddleware();
        $this->getLoggedInUser();
        $user = $this->user;
        $data = [
            'userId' => $user->id,
            'expiry' => now(),
            'updates' => now(),
            'support_expiry' => now(),
            'domain' => 'https://www.faveo.com',
            'code' => 'OLAPO08D903U890S',
            'orderNo' => 192020,

        ];
        $response = $this->json('GET', url('uploadFile'), $data);
        $response->assertStatus(302);
    }

    /** @group LocalizedLicense */
    public function test_downloadFile_clientDownloadsLicenseFile_return200WithFile()
    {
        $this->withoutMiddleware();
        $this->getLoggedInUser();
        $user = $this->user;
        $data = [
            'orderNo' => 192020,
        ];
        $response = $this->json('GET', url('downloadFile'), $data);
        $response->assertStatus(200);
        $response->assertHeader('content-disposition', 'attachment; filename="faveo-license-{192020}.txt"');
    }

    /** @group LocalizedLicense */
    public function test_downloadPrivate_clientDownloadsPrivateKey_returb()
    {
        $this->withoutMiddleware();
        $orderNo = 192020;
        $response = $this->json('GET', url('downloadPrivate/'.$orderNo));
        $response->assertStatus(200);
        $response->assertHeader('content-disposition', 'attachment; filename=privateKey-192020.txt');
    }

    /** @group LocalizedLicense */
    public function test_downloadFileAdmin_adminDownloadsLicense_should200WithFile()
    {
        $this->withoutMiddleware();
        $fileName = 'faveo-license-{192020}.txt';
        $response = $this->json('GET', url('LocalizedLicense/downloadLicense/'.$fileName));
        $response->assertStatus(200);
        $response->assertHeader('content-disposition', 'attachment; filename="faveo-license-{192020}.txt"');
    }

    /** @group LocalizedLicense */
    public function test_downloadPrivateKeyAdmin_adminDownloadPrivateKey_should200WithFile()
    {
        $this->withoutMiddleware();
        $fileName = 'faveo-license-{192020}.txt';
        $response = $this->json('GET', url('LocalizedLicense/downloadPrivateKey/'.$fileName));
        $response->assertStatus(200);
        $response->assertHeader('content-disposition', 'attachment; filename=privateKey-192020.txt');
    }

    /** @group LocalizedLicense */
    public function test_deleteFile_deleteLicenseFile_should()
    {
        $this->withoutMiddleware();
        $fileName = 'faveo-license-{192020}.txt';
        $response = $this->json('GET', url('LocalizedLicense/delete/'.$fileName));
        $response->assertStatus(302);
        $response->assertHeaderMissing('content-disposition', 'attachment; filename="faveo-license-{192020}.txt"');
    }

    /** @group LocalizedLicense */
    public function test_chooseLicenseMode_DatabaseChosen_returnStatusChangeSuccessfully()
    {
        $this->withoutMiddleware();
        \App\Model\Order\Order::create(['number' => 192020, 'order_status' => 'executed', 'product' => 28]);
        $data = [
            'choose' => 0,
            'orderNo' => 192020,
        ];
        $response = $this->json('POST', url('choose'), $data);
        $response->assertStatus(200);
        $response->assertJson(['success' => 'Status change successfully.']);
    }
}

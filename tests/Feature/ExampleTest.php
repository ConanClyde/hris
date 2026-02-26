<?php

test('returns a correct response', function () {
    $response = $this->get('/');

    $response->assertStatus(302);
    $response->assertRedirect(route('login'));
});

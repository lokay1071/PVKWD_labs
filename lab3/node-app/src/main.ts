import { NestFactory } from '@nestjs/core';
import { AppModule } from './app.module';
import { DocumentBuilder, SwaggerModule } from '@nestjs/swagger';

async function bootstrap() {
  const app = await NestFactory.create(AppModule);

  const config = new DocumentBuilder()
    .setTitle('NestJs API Documentation')
    .setDescription('Backend API for the NestJs application.')
    .setVersion('1.0')
    .addServer('http://localhost/node/')
    .addOAuth2({
      type: 'oauth2',
      description: 'Oauth2 security.',
      flows: {
        authorizationCode: {
          authorizationUrl: `http://localhost:8080/realms/Sheiierman/protocol/openid-connect/auth`,
          tokenUrl: `http://localhost:8080/realms/Sheiierman/protocol/openid-connect/token`,
          scopes: {
            openid: `OpenID Connect`,
            profile: `Profile`,
            email: `Email`,
          },
        },
        clientCredentials: {
          tokenUrl: `http://localhost:8080/realms/Sheiierman/protocol/openid-connect/token`,
          scopes: {
            openid: `OpenID Connect`,
            profile: `Profile`,
            email: `Email`,
          },
        },
      },
    })
    .addSecurityRequirements({ oauth2: [] })
    .build();

  const document = SwaggerModule.createDocument(app, config);
  SwaggerModule.setup('api', app, document);

  await app.listen(3000);
}
bootstrap();

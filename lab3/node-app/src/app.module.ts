import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { APP_GUARD } from '@nestjs/core';
import {
  KeycloakConnectModule,
  AuthGuard,
  ResourceGuard,
  RoleGuard,
  TokenValidation,
} from 'nest-keycloak-connect';
import { AppController } from './app.controller';
import { AppService } from './app.service';
import { CategoriesModule } from './categories/categories.module';
import { ProductsModule } from './products/products.module';

@Module({
  imports: [
    TypeOrmModule.forRoot({
      type: 'postgres',
      host: 'pg',
      port: 5432,
      username: 'pguser',
      password: 'password',
      database: 'nestjs',
      entities: [__dirname + '/**/*.entity{.ts,.js}'],
      synchronize: true,
      autoLoadEntities: true,
    }),
    KeycloakConnectModule.register({
      authServerUrl: 'http://localhost:8080',
      realm: 'Sheiierman',
      clientId: 'nodejs',
      secret: 'bcks58NxFY8aj2Kr73l5ab1Zwdn1Od7v',
      realmPublicKey:
        'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApj0agFRahtwOD0skKA2HBVN0jgppdr0MV98ptmnUsCUKOAF3xTM73ToQjOzjuhY9xugUC+6n0NTKmwTGrWb4739z0I32f+XMVCunQlKxPpgSpZKxhKTEiKEGz1pt27AYvTH17HG+8oTrXysYlLoGalVFdCsxecD8SafXH4c2NsxGLNtzUrehDoGfvNzB54f+PzOy6nSciL10M1ivdjAf/qcXUmqGXY8wPQ9lZ6csbo6/jVvbtIn3FfOrEeUpUgiaQ7dkaDTimo1z10O/i/0ju9xgk5tuCMp5WJ/2UKUtH+odwwUfeq6FayHTm4WzNULadxY8u2BlWonF831QTeBicQIDAQAB',
      tokenValidation: TokenValidation.OFFLINE,
    }),
    CategoriesModule,
    ProductsModule,
  ],
  controllers: [AppController],
  providers: [
    AppService,
    {
      provide: APP_GUARD,
      useClass: AuthGuard,
    },
    {
      provide: APP_GUARD,
      useClass: ResourceGuard,
    },
    {
      provide: APP_GUARD,
      useClass: RoleGuard,
    },
  ],
})
export class AppModule {}
